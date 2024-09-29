<?php

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FirebaseModel;
use PDF;
class NoteFirebaseController extends Controller
{
    protected $firebaseModel;

    public function __construct(FirebaseModel $firebaseModel)
    {
        $this->firebaseModel = $firebaseModel;
    }

    // Ajouter des notes pour un groupe d'apprenants d'un module
    public function addModuleNote(Request $request, $moduleId)
    {
        $notes = $request->input('notes');
        foreach ($notes as $noteData) {
            $apprenantId = $noteData['apprenantId'];
            $note = $noteData['note'];
            $this->firebaseModel->getDatabase()
                ->getReference('modules/' . $moduleId . '/apprenants/' . $apprenantId)
                ->set([
                    'note' => $note,
                ]);
        }

        return response()->json(['message' => 'Notes ajoutées au module avec succès.']);
    }
    public function addApprenantNotes(Request $request)
{
    $apprenantId = $request->input('apprenantId');
    $notes = $request->input('notes');

    foreach ($notes as $noteData) {
        $moduleId = $noteData['moduleId'];
        $note = $noteData['note'];

        // Enregistrer la note dans Firebase
        $this->firebaseModel->getDatabase()
            ->getReference('apprenants/' . $apprenantId . '/modules/' . $moduleId)
            ->set([
                'note' => $note,
            ]);
    }

    return response()->json(['message' => 'Notes ajoutées à l\'apprenant avec succès.']);
}
public function updateApprenantNotes(Request $request, $apprenantId)
{
    $notes = $request->input('notes');

    foreach ($notes as $noteData) {
        $noteId = $noteData['noteId'];
        $note = $noteData['note'];

        // Modifier la note dans Firebase
        $this->firebaseModel->getDatabase()
            ->getReference('apprenants/' . $apprenantId . '/notes/' . $noteId)
            ->update([
                'note' => $note,
            ]);
    }

    return response()->json(['message' => 'Notes modifiées avec succès.']);
}
public function listReferentielNotes($referentielId)
{
    // Récupérer les notes des apprenants depuis Firebase
    $notesSnapshot = $this->firebaseModel->getDatabase()
        ->getReference('referentiels/' . $referentielId . '/apprenants')
        ->getSnapshot();

    $notes = $notesSnapshot->getValue();

    return response()->json(['notes' => $notes]);
}


public function exportReferentielNotes($referentielId)
{
    // Récupérer les notes des apprenants depuis Firebase
    $notesSnapshot = $this->firebaseModel->getDatabase()
        ->getReference('referentiels/' . $referentielId . '/apprenants')
        ->getSnapshot();

    $notes = $notesSnapshot->getValue();

    // Générer le PDF avec dompdf
    // $pdf = PDF::loadView('releves.referentiel', compact('notes'));

    // return $pdf->download('releve_notes_referentiel_' . $referentielId . '.pdf');
}
public function exportApprenantNotes($apprenantId)
{
    // Récupérer les notes de l'apprenant depuis Firebase
    $notesSnapshot = $this->firebaseModel->getDatabase()
        ->getReference('apprenants/' . $apprenantId . '/modules')
        ->getSnapshot();

    $notes = $notesSnapshot->getValue();    

    // // Générer le PDF
    // $pdf = PDF::loadView('releves.apprenant', compact('notes'));

    // return $pdf->download('releve_notes_apprenant_' . $apprenantId . '.pdf');
}

}
