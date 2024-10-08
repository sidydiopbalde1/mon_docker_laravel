<?php

namespace App\Models;

use Exception;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;
use App\Jobs\SendRelanceEmail;
use Illuminate\Http\Request;
class FirebaseModel 
{
    protected $database;
    protected $auth;
    protected $storage;
    public function __construct()
    {
        try {
            $firebase_credentiels = base64_decode("ewogICJ0eXBlIjogInNlcnZpY2VfYWNjb3VudCIsCiAgInByb2plY3RfaWQiOiAibGFyYXZlbC1n
            ZXN0aW9uLXBlZGFnb2dpcXVlIiwKICAicHJpdmF0ZV9rZXlfaWQiOiAiZjY3Y2M1N2MzZjdiNDJm
            YjhkZGFhNmIzMTlhZTM5MTNkMTU3OTQ2OCIsCiAgInByaXZhdGVfa2V5IjogIi0tLS0tQkVHSU4g
            UFJJVkFURSBLRVktLS0tLVxuTUlJRXZBSUJBREFOQmdrcWhraUc5dzBCQVFFRkFBU0NCS1l3Z2dT
            aUFnRUFBb0lCQVFDY0RSZ3VEdXhZbkd5RlxuSlNjMnpmbmh5b1hOdGtMWmwvWm9HYUw2QmY5bVNs
            Vk5zOUV6VTh1YzRZTFZFTlExMVVOTGtaMXhydk5BY2FjcFxuK3g4Sm5pVGtSZUNEWWxPd1NzUm1C
            TWlkdW1hNWVtR1N4RmppYWRSSXV2a1J0cmVjcVF2Wkt0WE1rdnRORWxGdVxueXcza2cvMi9hZlo5
            ZDhyN0grVlZKTzNWTG1pRkZsaXIvcFEzWFN5cStBZTRzRTR3S1ZDR1lNUVpnV2FSdStJN1xucFhB
            NmpJOXZLMWhKNlR2YzllQWloYU5WTi8zRG5nNUY1ZGp4c25TbEREcU5KS2tDK3BKMGdlRXlyN1Z1
            VkhJZ1xuQ0hpd3FzajRhc1RNMlVPWDBSM01pNlJzcXkxZWN3SHIrYXJ4UE9ObEVBNE1SZTBiWE1w
            M3lPOFh2ZkZNTzNlWVxuWjBuSVRQbk5BZ01CQUFFQ2dnRUFEbEdmUmQ2bHJpM1FRVmxhNDByOElr
            MUFnTk00Q0JwTENGdE4ybzVCVktQK1xuZHM1SXBHS01mTjB1Y1ptRk4xbFZ2dkhpRmdQZEd5MHlq
            d1lWWDM4TTJJdFc0Z2RjTVlGeXl2K21YdDZ6UFZLdVxuOEZZbTU4T1pNSUZEaUc0UkpBVlk2bmps
            UEV2Q1ZseVhiMGlmZ2pmZ1NUZVNZQW5sYkt5TkVRWjk4a0U3WXZhNFxud0lpendpRHhJNWtMNmRr
            Rm1EbG1ybC9xZExtMTgyYlk2Q3BNZHI2TVRuRmVZaWZ6OHcwa0M5Z2FLQTF1ekZHTlxuSTFJL2xV
            bXNmT0o3alNWK2Z4NHQ4bmI5czNYUEdPL0VFUzFKWUMyZnFPSEpUcUlIcVBXVWNhWDZWdzhDRkZz
            WlxuV3R3ZkNQZUxqYWpWR3dNM081bzRqVlBrY2pIY1FTbWJvRzk4cGFkS3NRS0JnUURQZXkrMUda
            LzR2cVdRZ0o3dlxuSUlLb3l1S0lITE9JNEI5K0RxbE5xM2lLWFl6N01wR3FJaHIzYkcveGcyUVZv
            d09nTHdlU2dINVhmSUdOczVNVFxuQWg3QkFybEYxL0lzZWRITGhaMkVQblBpUG8xcmdyd3Zrd296
            SGttWUszY1JKZWtZZENOVkRLV2x4OVZDSUJjWlxuMGFTL3FEcXNGUVRxMWFhb0JwcldrWDVPNVFL
            QmdRREFpdzl3YXZSQndja3lCa2l4MUdudWp3Q2RaNnZZSjZCQVxuaXhteDZoMlRKa2VCVVdUNWZ3
            b0g1U1A2SkJlWnpybDh3cXI3TjhrcUF5VDU0RnRZTlBxdDVFSmxKSHU5UGpUVVxud0tHaWlwQ2x5
            SzU5MFpvejdRNEpGbjhDaHBSTEUyNDdZRkZlcmhpOWpXQjdWNjZYWmdKZFNpNzUrZ0VEeDcrZlxu
            ODcvWWRvUm95UUtCZ0ZBeHlmL1N0cjFiV3YwZWFjLzluZU1HQUVjaDZOYm40ZVJFWUhZUDU5aDdU
            a210Z3hYaVxuZHMvWmp1OG5uT0NzRUhPeW1kZXJhS29DQ3NVdGVwUm5SbUhOM3JTN1FmU2s1VTBv
            Q1BrYmRva2xLbDQ2dXVnY1xuaWwyMXdEWmIzbnEzVDRCUEszVHRIWDJWWHIxZlQrNS8xSkRyd2pu
            UEtnWm1yRExYMHJOS3F5cHRBb0dBYmNGWFxuUmEvU0dJSE1uSlYwYnRvMG9HWW41WklwSlFoQmsw
            azNsbkZyOW11RnF6T29xWWcvUVl6ZTIwSWpxZXRyVkxEclxuSWxVRWVNeGVFWXBReDVFN3JGQkhn
            THd4UlFqbXVMZ3Q2eWV0bUxNeXRFbjg2c2lnalpHalNOOUtQUm1RWm94YlxuRmV1TDh0RmFSRFVS
            TXdCVkxMU1MzckFpVC9OWFNtaDUzWHlCc2ZFQ2dZQkhZMzYrczRDdExIMXNHamg4S2dLQVxuL3dM
            cmxZMzNERWY4b25wdmw0Mi9Dd2RGRFdjNFJqakpuaGNUdThtWncyc3dJcU5ZOGF0OW9JNHgwY2NL
            M0pCT1xuUzhRTk40ZUg2bjRVN0tmcU9zcGp3ekkxeVR0NGlYbG5ZUmJraFFXeXlRYXNJM2VXZ1I2
            Y0lqQXU3LzBKenVRa1xuNjE4VVZiNEFYaG5NcTAxN1RRUm5wdz09XG4tLS0tLUVORCBQUklWQVRF
            IEtFWS0tLS0tXG4iLAogICJjbGllbnRfZW1haWwiOiAiZmlyZWJhc2UtYWRtaW5zZGstOTk4czdA
            bGFyYXZlbC1nZXN0aW9uLXBlZGFnb2dpcXVlLmlhbS5nc2VydmljZWFjY291bnQuY29tIiwKICAi
            Y2xpZW50X2lkIjogIjEwOTIzNDIxMTY1NTE5MDMyNzU3NSIsCiAgImF1dGhfdXJpIjogImh0dHBz
            Oi8vYWNjb3VudHMuZ29vZ2xlLmNvbS9vL29hdXRoMi9hdXRoIiwKICAidG9rZW5fdXJpIjogImh0
            dHBzOi8vb2F1dGgyLmdvb2dsZWFwaXMuY29tL3Rva2VuIiwKICAiYXV0aF9wcm92aWRlcl94NTA5
            X2NlcnRfdXJsIjogImh0dHBzOi8vd3d3Lmdvb2dsZWFwaXMuY29tL29hdXRoMi92MS9jZXJ0cyIs
            CiAgImNsaWVudF94NTA5X2NlcnRfdXJsIjogImh0dHBzOi8vd3d3Lmdvb2dsZWFwaXMuY29tL3Jv
            Ym90L3YxL21ldGFkYXRhL3g1MDkvZmlyZWJhc2UtYWRtaW5zZGstOTk4czclNDBsYXJhdmVsLWdl
            c3Rpb24tcGVkYWdvZ2lxdWUuaWFtLmdzZXJ2aWNlYWNjb3VudC5jb20iLAogICJ1bml2ZXJzZV9k
            b21haW4iOiAiZ29vZ2xlYXBpcy5jb20iCn0=");
            $factory = (new Factory)
                ->withServiceAccount(json_decode($firebase_credentiels, true))
                ->withDatabaseUri("https://laravel-gestion-pedagogique-default-rtdb.firebaseio.com/");
        
            $this->database = $factory->createDatabase();
            $this->auth = $factory->createAuth();
            $this->storage = $factory->createStorage();
        } catch (\Exception $e) {
            // Gérer l'erreur ici
            throw new \Exception('Erreur lors de la configuration de Firebase: ' . $e->getMessage());
        }
     
    }

    public function getDatabase()
    {
        return $this->database;
    }

    // Méthode pour créer une nouvelle entrée dans Firebase
    public function create($path, $data)
    {
        try {
            $reference = $this->database->getReference($path);
            $key = $reference->push()->getKey();
            $reference->getChild($key)->set($data);
            return $key;
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création dans Firebase : ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    // Méthode pour rechercher une entrée spécifique dans Firebase
    public function find($path, $id)
    {
        try {
            $reference = $this->database->getReference($path);
            $allReferentiels = $reference->getValue();
            foreach ($allReferentiels as $key => $referentiel) {
                if (isset($referentiel['id']) && $referentiel['id'] == (int)$id) {
                    return $referentiel;
                }
            }
            // dd($referentiel);
            return response()->json(['error' => 'noeuds non trouvé'], 404);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la recherche dans Firebase : ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function findNoeudById(string $referentielId, string $path)
    {
        // dd($referentielId, $path);
        // Chemin vers le noeud des référentiels dans la Realtime Database
        $referentielsRef = $this->database->getReference($path);
        
        // Récupérer toutes les données de référentiels
        $referentiels = $referentielsRef->getValue();
        // dd($referentiels);
        // Parcourir les référentiels pour trouver celui correspondant à l'ID
        foreach ($referentiels as $key => $referentiel) {
            if ($key === $referentielId) {
                return $referentiel; // Retourner le référentiel correspondant
            }
        }
        
        // Si le référentiel n'est pas trouvé, retourner null ou une exception
        return null; // ou throw new NotFoundException("Référentiel non trouvé");
    }
    public function findRefById($referentielId)
    {
        // dd($referentielId, $path);
        // Chemin vers le noeud des référentiels dans la Realtime Database
        $referentielsRef = $this->database->getReference("referentiels");
        
        // Récupérer toutes les données de référentiels
        $referentiels = $referentielsRef->getValue();
        // dd($referentiels);
        // Parcourir les référentiels pour trouver celui correspondant à l'ID
        foreach ($referentiels as $key => $referentiel) {
            if ($key === $referentielId) {
                return $referentiel; // Retourner le référentiel correspondant
            }
        }
        
        // Si le référentiel n'est pas trouvé, retourner null ou une exception
        return null; // ou throw new NotFoundException("Référentiel non trouvé");
    }
    public function findInactifs()
    {
        try {
            // Récupérer la promotion active
            $promotionActive = $this->getActivePromotion();
            
            // Vérifier si la promotion active existe
            if (!$promotionActive) {
                return response()->json(['message' => 'Aucune promotion active trouvée'], 404);
            }
    
            // Initialiser un tableau pour stocker les apprenants inactifs
            $apprenantsInactifs = [];
    
            // Parcourir les référentiels de la promotion active
            foreach ($promotionActive as $referentiel) {
                foreach ($referentiel['referentiels'] as $apprenant) {
                // dd($ref);
                if (isset($apprenant['apprenants'])) {
                    foreach ($apprenant['apprenants'] as $apprenant) {
                        // Vérifier si le statut de l'apprenant est "inactive"
                        if (isset($apprenant['statut']) && $apprenant['statut'] === 'Inactif') {
                            $apprenantsInactifs[] = $apprenant; // Ajouter l'apprenant à la liste des inactifs
                        }
                    }
                }
            }}
    
            // Vérifier s'il y a des apprenants inactifs
            if (empty($apprenantsInactifs)) {
                return response()->json(['message' => 'Aucun apprenant inactif trouvé'], 200);
            }
    
            // Préparer la réponse
            return response()->json(['apprenants_inactifs' => array_values($apprenantsInactifs)], 200);
    
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des apprenants inactifs : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur serveur'], 500);
        }
    }

    public function setCustomUserClaims($uid, array $claims)
    {
        $this->auth->setCustomUserClaims($uid, $claims);
    }
    
    public function filterByReferentielsAndStatus(array $filters)
    {
        try {
            $apprenants = $this->getAll("apprenants");
            $promotionEnCoursId = $this->getPromotionEnCoursId(); 
    
            if (!$promotionEnCoursId) {
                return response()->json(['error' => 'Aucune promotion active trouvée'], 404);
            }
    
            // Appliquer le filtre pour la promotion en cours par défaut si aucun filtre n'est fourni
            // if (empty($filters['referentiel'])) {
            //     $filters['referentiel'] = $promotionEnCoursId;
            // }
    
            // Filtrer les apprenants par le référentiel
            // if (!empty($filters['referentiel'])) {
            //     $apprenants = array_filter($apprenants, function ($apprenant) use ($filters) {
            //         return isset($apprenant['referentiels']['id']) && $apprenant['referentiels']['id'] == (int)$filters['referentiel'];
            //     });
            // }
           
            // Filtrer les apprenants par statut utilisateur
            if (!empty($filters['statut'])) {
                $apprenants = array_filter($apprenants, function ($apprenant) use ($filters) {
                    return isset($apprenant['user']['statut']) && $apprenant['user']['statut'] == $filters['statut'];
                });
            }
            return array_values($apprenants); 
    
        } catch (\Exception $e) {
            Log::error('Erreur lors du filtrage des apprenants dans Firebase : ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function findApprenantById($id, array $filters)
    {
        try {
            // Récupérer les données de l'apprenant dans Firebase
            $apprenant = $this->findNoeudById($id, "apprenants");
            // dd($apprenant);
            if (!$apprenant) {
                return response()->json(['message' => 'Apprenant non trouvé'], 404);
            }
    
            $referentiel = isset($apprenant['referentiels']) ? $apprenant['referentiels'] : null;
    
           
            $presences = isset($apprenant['presences']) ? $apprenant['presences'] : [];
            // dd($presences);
            if (isset($filters['presence'])) {
                $moisFiltre = $filters['presence'];
                // Filtrer les présences en fonction du mois fourni
                $presences = array_filter($presences, function ($mois, $data) use ($moisFiltre) {
                    return $mois === $moisFiltre;
                }, ARRAY_FILTER_USE_BOTH);
            }
    
            // Filtrage des notes par module
            // $notes = isset($apprenant['notes']) ? $apprenant['notes'] : [];
            $competences = $apprenant['referentiels']['competences'] ?? [];
            $notes = [];
            
            foreach ($competences as $competence) {
                foreach ($competence['modules'] as $module) {
                    // Vérifier si le module contient des notes
                    if (isset($module['note'])) {
                        $notes[] = [
                            'module_nom' => $module['description'],
                            'note1' => $module['note']['note1'] ?? null,
                            'note2' => $module['note']['note2'] ?? null
                        ];
                    }
                }
            }
            
            if (isset($filters['notes'])) {
                $moduleFiltre = $filters['notes'];
                // Filtrer les notes en fonction du module fourni
                $notes = array_filter($notes, function ($note) use ($moduleFiltre) {
                    return isset($note['description']) && $note['description'] == $moduleFiltre;
                });
            }
            
            $response = [
                'apprenant' => [
                    'id' => $id,
                    'nom' => $apprenant['user']['nom'],
                    'prenom' => $apprenant['user']['prenom'],
                    'email' => $apprenant['user']['email'],
                    'statut' => $apprenant['user']['statut'],
                    'photo' => $apprenant['user']['photoCouverture'],
                    'referentiel' => $referentiel,
                    'presences' => array_values($presences), 
                    'notes' => array_values($notes) 
                ]
            ];
    
            return response()->json($response, 200);
    
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des informations de l\'apprenant : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur serveur'], 500);
        }
    }
       
    private function getPromotionEnCoursId()
    {
        // Récupérer les promotions avec l'état "Actif"
        $promotionEnCours = $this->database->getReference('promotions')
            ->orderByChild('etat')
            ->equalTo('Actif')
            ->getValue();

        if (!empty($promotionEnCours)) {
            // Retourner l'ID de la première promotion active trouvée
            return key($promotionEnCours);
        }

        // Retourner null si aucune promotion active n'est trouvée
        return null;
    }
 
    // Méthode pour mettre à jour une entrée spécifique dans Firebase
    public function update($path, $id, $data)
    {
        try {
            $reference = $this->database->getReference($path);
            $allReferentiels = $reference->getValue();
            $referentielFound = false;
            foreach ($allReferentiels as $key => $referentiel) {
                if (isset($referentiel['id']) && $referentiel['id'] == (int)$id) {
                    $referentielFound = true;
                    break; // Sortir de la boucle si trouvé
                }
            }
    
            if (!$referentielFound) {
                return response()->json(['error' => 'Référentiel non trouvé'], 404);
            }
    
            // Mise à jour du neoud en utilisant la bonne clé
            $reference->getChild($key)->update($data);
            return response()->json(['success' => 'Mise à jour réussie']);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour dans Firebase : ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    // Méthode pour supprimer une entrée spécifique dans Firebase
    public function delete($path, $id)
    {
        try {
            $reference = $this->database->getReference($path);
            $allReferentiels = $reference->getValue();
    
            // Vérifier si le référentiel existe
            $referentielFound = false;
            $referentielKey = null; // Pour garder la clé du référentiel
    
            foreach ($allReferentiels as $key => $referentiel) {
                if ($referentiel['id'] == (int)$id) {
                    $referentielFound = true;
                    $referentielKey = $key; // Stocker la clé pour la mise à jour
                    break;
                }
            }
    
            if (!$referentielFound) {
                return response()->json(['error' => 'Référentiel non trouvé'], 404);
            }
    
            // Mettre à jour le champ 'actif' pour effectuer un soft delete
            $reference->getChild($referentielKey)->update(['actif' => false]); // Définir le champ actif à false
    
            return response()->json(['success' => 'Référentiel archivé avec succès']);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression dans Firebase : ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Méthode pour tester la connexion Firebase (optionnelle)
    public function test()
    {
        Log::info('Testing Firebase connection');
        $reference = $this->database->getReference('test');
        $reference->set([
            'date' => now()->toDateTimeString(),
            'content' => 'Firebase connection test',
        ]);
        Log::info('Data pushed to Firebase');
    }

    // Exemple d'utilisation pour stocker des données via une requête
    public function store($request)
    {
        $reference = $this->database->getReference('test'); // Remplacez 'test' par votre chemin
        $newData = $reference->push($request);
        return response()->json($newData->getValue());
    }
    // Méthode pour obtenir tous les utilisateurs depuis Firebase
    public function getAll($path)
    {
        try {
            $noeuds = $this->database->getReference($path);
            $noeuds = $noeuds->getValue(); 

            if ($noeuds) {
                return $noeuds; 
            } else {
                return []; 
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des utilisateurs dans Firebase : ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function findUserByEmail(string $email)
    {
        $users = $this->getAll('users');
        foreach ($users as $user) {
            if ($user['email'] === $email) {
                return $user;
            }
        }
        return null;
    }
    public function findUserByPhone(string $telephone)
    {
        $users = $this->getAll('users');
        // dd($users, $telephone);
        foreach ($users as $user) {
            if (isset($userData['telephone'])) {
            if ($user['telephone'] === $telephone) {
                return $user;
            }
        }
        }
        return null;
    }
    public function createUserWithEmailAndPassword($email, $password)
    {
        Log::info('Création d\'un utilisateur Firebase avec email : ' . $email);
        try {
            $user = $this->auth->createUser([
                'email' => $email,
                'password' => $password,
            ]);
            return $user->uid;
            // Log::info('Utilisateur Firebase créé avec UID : ' . $user->uid);
    
        } catch (Exception $e) {
            Log::error('Firebase Auth Error: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de la création de l\'utilisateur Firebase.'], 500);
        } catch (Exception $e) {
            Log::error('Firebase Exception: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur avec Firebase.'], 500);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de l\'utilisateur Firebase : ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de la création de l\'utilisateur dans Firebase.'], 500);
        }
    }
    
    public function uploadImageToStorage($filePath, $fileName)
    {
        try {
            // Récupérer le bucket de Firebase Storage
            $bucket = $this->storage->getBucket();

            // Ouvrir le fichier et le télécharger
            $file = fopen($filePath, 'r');
            $bucket->upload($file, [
                'name' => $fileName // Nom du fichier dans le bucket
            ]);

            // Obtenez l'URL de téléchargement
            $object = $bucket->object($fileName);
            $url = $object->signedUrl(new \DateTime('tomorrow')); // URL temporaire d'un jour

            Log::info('Image téléchargée avec succès sur Firebase Storage : ' . $url);
            return $url;

        } catch (\Exception $e) {
            Log::error('Erreur lors du téléchargement de l\'image dans Firebase Storage : ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getLastReferentiel()
    {
        // Récupérer tous les référentiels depuis Firebase
        $referentiels = $this->database->getReference('referentiels')->getValue();

        // Si aucun référentiel n'existe, renvoyer null
        if (!$referentiels) {
            return null; // Pas de référentiels existants
        }

        // Utiliser `collect` pour trier les référentiels par ID décroissant
        return collect($referentiels)->sortByDesc('id')->first();
    }
    public function deactivateOtherPromotions()
    {
        try {
            
            $promotions = $this->database->getReference('promotions')->getValue();

            if ($promotions) {
                foreach ($promotions as $key => $promotion) {
                    if (isset($promotion['etat']) && $promotion['etat'] === 'Actif') {
                        // Désactiver les autres promotions actives
                        $this->database->getReference("promotions/{$key}/etat")->set('Inactif');
                    }
                }
            }
        } catch (Exception $e) {
            throw new \Exception("Erreur lors de la désactivation des promotions: " . $e->getMessage());
        }
    }
    //get activePromotions
    public function getActivePromotion()
    {
        $promotions = $this->getAll('promotions');
        // dd($promotions);
        if ($promotions) {
            return collect($promotions)->where('etat', 'Actif')->all();
        }

        return [];
    }

    //---------------------------Gestion_Notes------------------------
    public function addNotesToApprenant(string $apprenantId, array $notes)
    {
        // Appeler la méthode qui gère l'ajout ou la mise à jour des notes dans Firebase
        $result = $this->addOrUpdateNotesInFirebase($apprenantId, $notes);
    
        // Retourner le résultat de l'opération
        if ($result['status'] === 200) {
            return ['success' => 'Notes mises à jour avec succès.', 'status' => 200];
        } else {
            return ['error' => $result['error'], 'status' => $result['status']];
        }
    }
    
    public function addOrUpdateNotesInFirebase(string $apprenantId, array $newNotes)
    {
        $reference = $this->database->getReference('apprenants/'.$apprenantId);
        $apprenant = $reference->getValue();
        
        if (!$apprenant || !is_array($apprenant)) {
            return ['error' => 'Apprenant non trouvé ou données invalides.', 'status' => 404];
        }
        if (!isset($apprenant['referentiels']) || !is_array($apprenant['referentiels'])) {
            return ['error' => 'Aucun référentiel trouvé pour cet apprenant ou structure des données incorrecte.', 'status' => 404];
        }
        foreach ($newNotes as $newNote) {
            $moduleFound = false;
            foreach ($apprenant['referentiels'] as $referentiel) {
                    $competences=$apprenant['referentiels']['competences'];
                foreach ($competences as &$competence) {
                    foreach ($competence['modules'] as &$module) {
                        if (isset($module['nom']) && $module['nom'] === $newNote['module']) {
                            if (!isset($module['notes']) || !is_array($module['notes'])) {
                                $module['notes'] = [];
                            }
                            $module['notes'][] = $newNote['note'];
                        
                            $module['moyenne'] = array_sum($module['notes']) / count($module['notes']);
                            $moduleFound = true;
                            // dd($apprenant['referentiels']);
                            break;
                        }
                    }

                    if ($moduleFound) {
                        break;
                    }
                }
                if (!$moduleFound) {
                    // Ajouter un nouveau module avec la note
                    $referentiel['competences'][] = [
                        'nom' => $newNote['module'],
                        'notes' => [$newNote['note']],
                        'moyenne' => $module['moyenne'] , // Première note ajoutée devient la moyenne
                        'appreciation' => '', // Champ d'appréciation vide par défaut (ou autre logique)
                        'description' => '', // Description vide ou autre logique
                        'duree_acquisition' => 0 // Durée par défaut ou autre logique
                    ];
                }
            }
        }
    
            // Utilisation de set() pour remplacer les référentiels
    $ref=$this->database->getReference('apprenants/'.$apprenantId)->getChild('referentiels')->set($apprenant['referentiels']);
    // dd($ref);
        // Retourner la réponse de succès
        return ['success' => 'Notes mises à jour avec succès.', 'status' => 200,'data' => $ref];
    }
    
    public function updateNotes(Request $request, $apprenantId)
    {
        $reference = $this->database->getReference('apprenants/' . $apprenantId);
        $apprenant = $reference->getValue();

        // Vérification si l'apprenant existe
        if (!$apprenant || !is_array($apprenant)) {
            return response()->json(['error' => 'Apprenant non trouvé ou données invalides.'], 404);
        }

        // Boucle sur les nouvelles notes pour mise à jour
        foreach ($request->input('notes') as $newNote) {
            $noteId = $newNote['noteId'];
            $noteValue = $newNote['note'];

            // Parcourir les référentiels et modules pour trouver et mettre à jour la note
            foreach ($apprenant['referentiels'] as &$referentiel) {
                foreach ($referentiel['competences'] as &$competence) {
                    foreach ($competence['modules'] as &$module) {
                        if (isset($module['notes'][$noteId])) {
                            $module['notes'][$noteId] = $noteValue;
                            $module['moyenne'] = array_sum($module['notes']) / count($module['notes']);
                        }
                    }
                }
            }
        }

        // Mettre à jour dans Firebase
        $reference->set($apprenant);
        return response()->json(['success' => 'Notes mises à jour avec succès.'], 200);
    }

    public function listNotesByReferentiel($referentielId)
    {
        $reference = $this->database->getReference('apprenants');
        $apprenants = $reference->getValue();

        if (!$apprenants || !is_array($apprenants)) {
            return response()->json(['error' => 'Aucun apprenant trouvé.'], 404);
        }

        $notes = [];
        foreach ($apprenants as $apprenantId => $apprenant) {
            foreach ($apprenant['referentiels'] as $referentiel) {
                if ($referentiel['id'] == $referentielId) {
                    $notes[$apprenantId] = $referentiel['competences'];
                }
            }
        }

        return response()->json(['notes' => $notes], 200);
    }

    
    

    public function addModuleNotes1($moduleId, Request $request)
    {
        // Récupérer les données de la requête
        $notesData = $request->input('notes');
        
        if (empty($notesData)) {
            return response()->json(['error' => 'Aucune donnée de note fournie.'], 400);
        }
    
        // Récupérer la promotion active (à adapter à votre logique pour récupérer la promotion en cours)
        $promotion = $this->getLibelleActivePromotion(); // Adaptez cette méthode à votre logique
    
        if (!$promotion) {
            return response()->json(['error' => 'Aucune promotion en cours.'], 404);
        }
    
        // Récupérer les apprenants par promotion
        $apprenantsInPromotion = $this->getApprenantsByPromotion($promotion);
    
        if (empty($apprenantsInPromotion)) {
            return response()->json(['error' => 'Aucun apprenant trouvé pour la promotion en cours.'], 404);
        }
    
        // Boucle sur chaque ensemble de notes fournies
        foreach ($notesData as $noteData) {
            $apprenantIds = $noteData['apprenantIds']; // IDs des apprenants concernés
            $note = $noteData['note'];
    
            // Boucle sur chaque apprenant pour ajouter la note
            foreach ($apprenantIds as $apprenantId) {
                if (!isset($apprenantsInPromotion[$apprenantId])) {
                    // Vérifier si l'apprenant est dans la promotion en cours
                    return response()->json(['error' => "Apprenant ID: $apprenantId n'appartient pas à la promotion."], 403);
                }
    
                // Récupérer les données de l'apprenant depuis Firebase
                $apprenantData = $this->findNoeudById($apprenantId, 'apprenants');
                
                if (!$apprenantData) {
                    return response()->json(['error' => "Apprenant ID: $apprenantId non trouvé."], 404);
                }
    
                // Parcourir les compétences de l'apprenant
                foreach ($apprenantData['competences'] as &$competence) {
                    foreach ($competence['modules'] as &$module) {
                        // Vérifier si c'est le module correct
                        if ($module['id'] === $moduleId) {
                            // Ajouter la note si elle n'existe pas déjà
                            $module['notes'][] = $note;
    
                            // Recalculer la moyenne
                            $module['moyenne'] = array_sum($module['notes']) / count($module['notes']);
    
                            // Mettre à jour les données dans Firebase
                            $this->updateApprenantInFirebase($apprenantId, ['competences' => $apprenantData['competences']]);
                            
                            break; // Sortir de la boucle des modules une fois la note ajoutée
                        }
                    }
                }
            }
        }
    
        return response()->json(['message' => 'Les notes ont été ajoutées avec succès.'], 200);
    }
     
    // Méthode pour mettre à jour l'apprenant dans Firebase
    protected function updateApprenantInFirebase($apprenantId, $data)
    {
        $this->database->getReference("apprenants/{$apprenantId}")->update($data);
    }
    
    // Méthode pour récupérer les apprenants par promotion dans Firebase
    protected function getApprenantsByPromotion()
    {
            // Récupérer la promotion active
            $promotion = $this->getActivePromotion();
                
            // Extraire la première clé de la promotion (car la structure a une clé aléatoire unique)
            $promotionKey = array_key_first($promotion);
            
            // Accéder aux référentiels à partir de la clé trouvée
            $referentiels = $promotion[$promotionKey]['referentiels'];
            
            // Initialiser un tableau pour stocker les apprenants
            $apprenants = [];
            
            // Vérifier que les référentiels existent et sont bien un tableau
            if (is_array($referentiels)) {
                // Parcourir chaque référentiel
                foreach ($referentiels as $referentiel) {
                    // Vérifier que le référentiel contient des apprenants
                    if (isset($referentiel['apprenants']) && is_array($referentiel['apprenants'])) {
                        // Ajouter chaque apprenant au tableau
                        foreach ($referentiel['apprenants'] as $apprenant) {
                            $apprenants[] = $apprenant;
                        }
                    }
                }
            }
            // dd($apprenants);
            // Retourner la liste des apprenants
            return $apprenants;
    }
    
    
    // protected function getIdActivePromotion()
    // {
    //     // Remplacez cela par la logique pour récupérer la promotion active
    //     $activePromotion = $this->database->getReference('promotions')
    //         ->orderByChild('etat')
    //         ->equalTo('Actif')
    //         ->getSnapshot();

    //     return $activePromotion->getValue() ? key($activePromotion->getValue()) : null; // Retourne l'ID de la première promotion active trouvée
    // }
    protected function getLibelleActivePromotion()
    {
        // Récupérer les données des promotions en fonction de l'état 'Actif'
        $activePromotionSnapshot = $this->database->getReference('promotions')
            ->orderByChild('etat')
            ->equalTo('Actif')
            ->getSnapshot();

        $activePromotions = $activePromotionSnapshot->getValue();

        if ($activePromotions) {
            // Parcourir les promotions actives (au cas où il y aurait plusieurs)
            foreach ($activePromotions as $promoData) {
                if (isset($promoData['libelle'])) {
                    // Retourner le libellé de la première promotion active trouvée
                    return $promoData['libelle'];
                }
            }
        }

        // Si aucune promotion active n'est trouvée, retourner null ou une chaîne vide
        return null;
    }

}

