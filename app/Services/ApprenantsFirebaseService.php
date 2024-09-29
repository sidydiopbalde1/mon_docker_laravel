<?php
namespace App\Services;

use App\Exports\ApprenantsErreursExport;
use App\Repository\ApprenantsFirebaseRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use SendApprenantCredentials as GlobalSendApprenantCredentials;
use App\Jobs\SendRelanceJob;
use App\Services\UserFirebaseService;
use App\Repository\UserFirebaseRepository;
use Illuminate\Http\Request;

class ApprenantsFirebaseService implements ApprenantsFirebaseServiceInterface
{
    protected $apprenantsRepository;
    protected $qrCodeService;
    protected $pdfService;
    protected $referentielService;
    public function __construct(ApprenantsFirebaseRepository $apprenantsRepository,ReferentielFirebaseService  $referentielService, QrCodeService $qrCodeService, PdfService $pdfService)
    {
        $this->apprenantsRepository = $apprenantsRepository;
        $this->referentielService = $referentielService;
        $this->qrCodeService = $qrCodeService;
        $this->pdfService = $pdfService;
    }
    public function createApprenant(array $data)
    {
        // Vérifier que les informations utilisateur sont fournies dans $data
        if (!isset($data['nom']) || !isset($data['prenom']) || !isset($data['email'])) {
            return ['error' => 'Les informations de l\'utilisateur (nom, prénom, email) sont requises.'];
        }
    
        // Génération du matricule
        $matricule = $this->generateMatricule();
    
        // Créer l'utilisateur dans Firebase (simultanément avec l'apprenant)
        $userData = [
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'email' => $data['email'],
            'photoCouverture' => $data['photo'] ?? null,
            'fonction' => $data['fonction_id'] ?? null,
            'adresse' => $data['adresse'] ?? null,
            'telephone' => $data['telephone'] ?? null,
            'statut' => "Inactif",
            'matricule' => $matricule,
            'referentiel_id' => $data['referentiel_id'],
        ];
    
        // Création de l'utilisateur dans Firebase
        $userFirebaseService = new UserFirebaseService(new UserFirebaseRepository());
        $userFirebaseService->createUser($userData);
    
        // Récupérer les données du référentiel (incluant les compétences et modules existants)
        $referentielData = $this->referentielService->findRefById($data['referentiel_id']);
        if (!$referentielData) {
            return ['error' => 'Référentiel non trouvé.'];
        }
    
        // Préparer les données de l'apprenant pour Firebase
        $firebaseData = [
            'user' => $userData,
            'referentiels' => $referentielData,
            'presences' => [], // Initialisation des présences
            'competences' => $referentielData['competences'], // Utiliser les compétences du référentiel
            'statut' => 'En attente',
            'matricule' => $matricule,
        ];
    
        // Ajouter les présences en fonction du format d'entrée
        if (isset($data['presences'])) {
            foreach ($data['presences'] as $mois => $dates) {
                foreach ($dates as $date => $emargements) {
                    foreach ($emargements as $emargementKey => $emargementData) {
                        // Conversion de la date au format correct
                        $formattedDate = str_replace('/', '-', $date);
                        // Ajout des données d'émargement dans Firebase
                        $firebaseData['presences'][$mois][$formattedDate][$emargementKey] = [
                            'entree' => $emargementData['entree'] ?? null,
                            'sortie' => $emargementData['sortie'] ?? null,
                        ];
                    }
                }
            }
        }
    
        // Ajouter ou mettre à jour les notes, moyennes et appréciations dans les compétences/modules existants
        if (isset($data['competences'])) {
            foreach ($data['competences'] as $competenceKey => $modules) {
                // Vérifier si la compétence existe dans le référentiel
                if (isset($firebaseData['competences'][$competenceKey])) {
                    foreach ($modules as $moduleKey => $moduleData) {
                        // Vérifier si le module existe dans la compétence du référentiel
                        if (isset($firebaseData['competences'][$competenceKey]['modules'][$moduleKey])) {
                            // Calcul de la moyenne des notes du module
                            $notes = $moduleData['notes'] ?? [];
                            $moyenne = count($notes) > 0 ? array_sum($notes) / count($notes) : 0;
    
                            // Mettre à jour les notes, moyennes, et appréciations sans ajouter d'ID
                            $firebaseData['competences'][$competenceKey]['modules'][$moduleKey]['notes'] = $notes;
                            $firebaseData['competences'][$competenceKey]['modules'][$moduleKey]['moyenne'] = $moyenne;
                            $firebaseData['competences'][$competenceKey]['modules'][$moduleKey]['appreciation'] = $moduleData['appreciation'] ?? null;
                        }
                    }
                }
            }
        }
    
        // Stocker les données de l'apprenant dans Firebase
        $firebaseKey = $this->apprenantsRepository->create($firebaseData);
    
        // Gestion des erreurs liées à l'enregistrement Firebase
        if (isset($firebaseKey['error'])) {
            return $firebaseKey;
        }
    
        // Génération du QR code et préparation des paramètres pour l'envoi
        $qrcode = $this->qrCodeService->generateQrCode($matricule, "path");
        $params = [
            'nom' => $userData['nom'],
            'password' => "passer123",
            'email' => $userData['email'],
            'matricule' => $matricule,
            'qrcode' => $qrcode
        ];
    
        // Dispatch du job pour envoyer l'email avec les informations
        SendRelanceJob::dispatch($params);
    
        // Retourner la clé Firebase de l'apprenant créé
        return $firebaseKey;
    }
    
    public function addNotesToApprenant(string $apprenantId, array $notes)
    {
        return $this->apprenantsRepository->addNotesToApprenant($apprenantId, $notes);
    }
    
    
    
    
    public function addModuleNotes($moduleId,Request $request)
    {
        return $this->apprenantsRepository->addModuleNotes($moduleId, $request);
    }
    public function importApprenants($file, $referentielId)
    {
        $referentielData = $this->referentielService->findRefById($referentielId);
        $apprenants = Excel::toArray([], $file)[0];
        // dd($file, $apprenants);
        $failedApprenants = [];
        
        foreach ($apprenants as $data) {
            $apprenant = $this->apprenantsRepository->create([
                'nom' => $data[1],
                'prenom' => $data[2],
                'date_naissance' => $data[3],
                'sexe' => $data[4],
                'email' => $data[5],
                'referentiel' => $referentielData,
            ]);
            $matricule = $this->generateMatricule();
            // $apprenant->matricule = $matricule;      
            $qrCodePath = $this->qrCodeService->generateQrCode($apprenant->id, $matricule);
            $defaultPassword = 'defaultPassword123!'; 
            GlobalSendApprenantCredentials::dispatch($apprenant, $defaultPassword);
        }
        return $this->createErrorFile($failedApprenants);
    }

    protected function createErrorFile($apprenantsAvecErreurs)
    {
        // Crée un fichier Excel avec les erreurs et stocke-le
        $filePath = 'apprenants_erreurs.xlsx';
        Excel::store(new ApprenantsErreursExport($apprenantsAvecErreurs), $filePath);
        return Storage::path($filePath);
    }

    private function generateMatricule()
    {
        return 'MATRICULE_' . uniqid();
    }
  
    public function sendGroupRelance()
    {
        // Récupère les apprenants inactifs
        $response = $this->findApprenantsInactif();
        // dd($response);
        if (isset($response->original['apprenants_inactifs'])) {
            $apprenants = $response->original['apprenants_inactifs'];
    
            foreach ($apprenants as $apprenant) {
                // Génère le QR code
                $qrcode = $this->qrCodeService->generateQrCode($this->generateMatricule(), "path");
    
                // Prépare les paramètres à envoyer dans le job
                $params = [
                    'nom' => $apprenant['nom'],
                    'password' => "passer123",
                    'email' => $apprenant['email'],
                    'matricule' => $this->generateMatricule(),
                    'qrcode' => $qrcode
                ];
    
                // Dispatch du job avec les paramètres
                SendRelanceJob::dispatch($params);
            }
    
            return response()->json(['message' => 'Relance envoyée à tous les apprenants inactifs.'], 200);
        } else {
            return response()->json(['message' => 'Aucun apprenant inactif trouvé.'], 404);
        }
    }
    
    public function sendAppRelanceById($id)
    {
        $apprenant=$this->apprenantsRepository->relancerApprenantById($id);
        // dd($apprenant);
        $qrcode = $this->qrCodeService->generateQrCode($this->generateMatricule(), "path");
                $params = [
                    'nom' => $apprenant['nom'],
                    'password' => "passer123",
                    'email' => $apprenant['email'],
                    'matricule' => $this->generateMatricule(),
                    'qrcode' => $qrcode
                ];
        SendRelanceJob::dispatch($params); 
    }
    
    public function findApprenantsById($id){
        return $this->apprenantsRepository->find($id);  // Retourne l'apprenant trouvé ou null si non trouvé
    }

    public function findApprenantsInactif()
    {
        return $this->apprenantsRepository->findInactifs();
    }
    public function updateApprenants(string $id, array $data)
    {
        return $this->apprenantsRepository->update($id, $data);
    }

    public function deleteApprenants(string $id)
    {
        return $this->apprenantsRepository->delete($id);
    }

    public function findApprenants(string $id)
    {
        return $this->apprenantsRepository->find($id);
    }
    public function findApprenantBy_ID(string $id, array $filters){
        return $this->apprenantsRepository->findApprenantBy_ID($id,$filters);  // Retourne l'apprenant trouvé ou null si non trouvé

    }
    public function filterApprenants(array $filters)
    {
        return $this->apprenantsRepository->filterByReferentielsAndStatus($filters);
    }
    public function getAllApprenants()
    {
        return $this->apprenantsRepository->getAll();
    }

    public function findApprenantsByEmail(string $email)
    {
        return $this->apprenantsRepository->findUserByEmail($email); 
    }
    public function findUserByPhone(string $telephone)
    {
        return $this->apprenantsRepository->findUserByPhone($telephone); 
    }
    public function createUserWithEmailAndPassword($email,$password)
    {
        return $this->apprenantsRepository->createUserWithEmailAndPassword($email,$password); 
    }
    public function uploadImageToStorage($filePath, $fileName)
    {
        return $this->apprenantsRepository->uploadImageToStorage($filePath, $fileName); 
    }
}