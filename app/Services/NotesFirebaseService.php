<?php

namespace App\Services;

use App\Repository\NoteFirebaseRepository;
class NotesFirebaseService 
{
    protected $noterepository;

    // public function __construct(NoteFirebaseRepository $noterepository)
    // {
    //     $this->noterepository = $noterepository;
    // }
    // public function addModuleNotes(Request $request, $moduleId)
    // {
    //     // Valider les données de la requête
    //     $validatedData = $request->validate([
    //         'notes' => 'required|array',
    //         'notes.*.apprenantId' => 'required|integer',
    //         'notes.*.note' => 'required|numeric|min:0|max:20',
    //     ]);

    //     $notes = $validatedData['notes'];

    //     // Récupérer la promotion en cours depuis Firebase
    //     $promotion = $this->firebaseService->getCurrentPromotion();
    //     if (!$promotion) {
    //         return response()->json(['error' => 'Aucune promotion en cours trouvée.'], 404);
    //     }

    //     // Parcourir les référentiels de la promotion
    //     foreach ($promotion['referentiels'] as $referentiel) {
    //         // Vérifier si le module existe dans ce référentiel
    //         foreach ($referentiel['competences'] as $competence) {
    //             foreach ($competence['modules'] as $module) {
    //                 if ($module['nom'] === $moduleId) {
    //                     // Mettre à jour les notes des apprenants
    //                     foreach ($notes as $noteData) {
    //                         $apprenantId = $noteData['apprenantId'];
    //                         $note = $noteData['note'];

    //                         // Rechercher l'apprenant dans ce référentiel
    //                         foreach ($referentiel['apprenants'] as $apprenant) {
    //                             if ($apprenant['id'] == $apprenantId) {
    //                                 // Mettre à jour la note dans Firebase
    //                                 $this->firebaseService->updateModuleNote($apprenantId, $moduleId, $note, $referentiel['id'], $promotion['id']);
    //                             }
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     }

    //     return response()->json(['message' => 'Notes mises à jour avec succès.']);
    // }

}