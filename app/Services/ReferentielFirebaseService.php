<?php
namespace App\Services;
use App\Repository\ReferentielFirebaseRepository;
use App\Repository\ReferentielFirebaseRepositoryInterface;
use Exception;
use Illuminate\Support\Str;
class ReferentielFirebaseService implements ReferentielFirebaseServiceInterface
{
    protected $referentielRepository;
    public function __construct(ReferentielFirebaseRepository $referentielRepository)
    {
        $this->referentielRepository = $referentielRepository;
    }
    // Ajout d'une compétence à un référentiel
    public function addCompetenceToReferentiel(string $referentielId, array $competenceData)
    {
        $referentiel = $this->referentielRepository->find($referentielId);
        
        if (!$referentiel) {
            throw new Exception("Référentiel non trouvé.");
        }
        $competence = $this->formatCompetencesAsObjects($competenceData);
        $referentiel['competences'][] = $competence;
        return $this->referentielRepository->update($referentielId, ['competences' => $referentiel['competences']]);
    }
        // Suppression d'une compétence (soft delete)
    public function removeCompetenceFromReferentiel(string $referentielId, string $competenceNom)
    {
        $referentiel = $this->referentielRepository->find($referentielId);
        if (!$referentiel) {
            throw new Exception("Référentiel non trouvé.");
        }
        // Marquer la compétence comme archivée au lieu de la supprimer définitivement
        foreach ($referentiel['competences'] as &$competence) {
            if ($competence['nom'] === $competenceNom) {
                $competence['deleted_at'] = now(); // Soft delete
                break;
            }
        }
        return $this->referentielRepository->update($referentielId, ['competences' => $referentiel['competences']]);
    }
 // Ajout de modules à une compétence existante
    public function addModuleToCompetence(string $referentielId, string $competenceNom, array $moduleData)
    {
        $referentiel = $this->referentielRepository->find($referentielId);
        if (!$referentiel) {
            throw new Exception("Référentiel non trouvé.");
        }
        foreach ($referentiel['competences'] as &$competence) {
            if ($competence['nom'] === $competenceNom) {
                $competence['modules'][] = $this->formatModules($moduleData);
                break;
            }
        }
        return $this->referentielRepository->update($referentielId, ['competences' => $referentiel['competences']]);
    }
    // Suppression d'un module d'une compétence
    public function removeModuleFromCompetence(string $referentielId, string $competenceNom, string $moduleNom)
    {
        $referentiel = $this->referentielRepository->find($referentielId);
        if (!$referentiel) {
            throw new Exception("Référentiel non trouvé.");
        }
        foreach ($referentiel['competences'] as &$competence) {
            if ($competence['nom'] === $competenceNom) {
                foreach ($competence['modules'] as &$module) {
                    if ($module['nom'] === $moduleNom) {
                        $module['deleted_at'] = now(); // Soft delete du module
                        break;
                    }
                }
            }
        }
        return $this->referentielRepository->update($referentielId, ['competences' => $referentiel['competences']]);
    }
    public function createReferentiel(array $data)
    {
        // Générer un identifiant numérique aléatoire pour le référentiel (par exemple, entre 100000 et 999999)
        $randomId = random_int(100000, 999999);
    
        // Structure du référentiel à sauvegarder
        $referentielData = [
            'id' => $randomId,  // ID aléatoire généré
            'code' => $data['code'],
            'libelle' => $data['libelle'],
            'description' => $data['description'],
            'statut' => 'Actif',  // Par défaut, statut à "Actif"
            'photo' => $data['photo'] ?? null,
            'competences' => $this->formatCompetencesAsObjects($data['competences'] ?? [])
        ];
    
        // Sauvegarde dans la base de données via le repository
        return $this->referentielRepository->create($referentielData);
    }
    
    public function formatCompetencesAsObjects(array $competences)
    {
        $formattedCompetences = [];
        foreach ($competences as $key => $competence) {
            $formattedCompetences[$key] = [
                'nom' => $competence['nom'],
                'description' => $competence['description'],
                'duree_acquisition' => $competence['duree_acquisition'],
                'type' => $competence['type'],
                'modules' => $this->formatModules($competence['modules'] ?? [])
            ];
        }
        return $formattedCompetences;
    }
    
    public function formatModules(array $modules)
    {
        $formattedModules = [];
        foreach ($modules as $module) {
            // Générer un identifiant aléatoire pour chaque module
            $moduleId = bin2hex(random_bytes(8));  // Génère un identifiant aléatoire de 16 caractères hexadécimaux
    
            // Calcul de la moyenne des notes si des notes sont fournies
            $notes = $module['notes'] ?? [];
            $moyenne = count($notes) > 0 ? array_sum($notes) / count($notes) : null;
    
            $formattedModule = [
                'id' => $moduleId,  // ID aléatoire généré pour le module
                'nom' => $module['nom'],
                'description' => $module['description'],
                'duree_acquisition' => $module['duree_acquisition'],
                'notes' => $notes,  // Les notes du module
                'moyenne' => $moyenne,  // Calcul de la moyenne des notes
                'appreciation' => $module['appreciation'] ?? null // Appréciation facultative
            ];
    
            $formattedModules[] = $formattedModule;
        }
        return $formattedModules;
    }
    
    
    
    public function getCompetencesByReferentiel(string $referentielId)
    {
        return $this->referentielRepository->getCompetencesByReferentiel($referentielId);
    }
    public function getModulesByReferentiel(string $referentielId)
    {
        return $this->referentielRepository->getModulesByReferentiel($referentielId);
    }
    public function updateReferentiel(string $id, array $data)
    {
        return $this->referentielRepository->update($id, $data);
    }
    public function deleteReferentiel(string $id)
    {
        return $this->referentielRepository->delete($id);
    }
    public function findReferentiel(string $id)
    {
        // dd($this->referentielRepository->findNoeudById($id));
        return $this->referentielRepository->find($id);
    }
    public function findRefById($id)
    {
        return $this->referentielRepository->findRefById($id);
    }
    public function getAllActiveReferentiels($statut)
    {
        return $this->referentielRepository->getAllStatut($statut);
    }
    public function archiveReferentiel(string $id)
    {
        // Vérification si le référentiel est utilisé dans une promotion en cours
        // Logique métier pour empêcher l'archivage
        return $this->referentielRepository->softDelete($id);
    }
    public function getArchivedReferentiels()
    {
        return $this->referentielRepository->getArchived();
    }
    public function uploadImageToStorage($filePath, $fileName)
    {
        return $this->referentielRepository->uploadImageToStorage($filePath, $fileName); 
    }
    // public function getLastReferentiel()
    // {
    //     return $this->referentielRepository->getLastReferentiel();
    // }
}