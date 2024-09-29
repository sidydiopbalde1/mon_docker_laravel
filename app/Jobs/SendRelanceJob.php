<?php
namespace App\Jobs;

use App\Mail\ApprenantCredentialsMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendRelanceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $params;

    public function __construct(array $params)
    {
        // Stockage des paramètres dans un tableau
        $this->params = $params;
    }

    public function handle()
    {
        // Vérification des paramètres essentiels
        if (!isset($this->params['email']) || !isset($this->params['password']) || !isset($this->params['matricule'])) {
            throw new \Exception("Les informations de l'apprenant sont incomplètes.");
        }

        // Envoi de l'email avec les informations de l'apprenant
        Mail::to($this->params['email'])->send(
            new ApprenantCredentialsMail($this->params)
        );
    }
}
