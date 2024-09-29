<?php

use App\Mail\ApprenantCredentialsMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendApprenantCredentials implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $params;

    /**
     * Crée une nouvelle instance du job.
     *
     * @param array $params
     */
    public function __construct(array $params)
    {
        // Vérification que l'email est fournie
        if (empty($params['email'])) {
            throw new \Exception("L'email de l'apprenant est manquante.");
        }

        $this->params = $params;
    }

    /**
     * Gérer le job pour envoyer l'email.
     *
     * @return void
     */
    public function handle()
    {
        // Envoi de l'email avec les informations de connexion
        if (!empty($this->params['email'])) {
            Mail::to($this->params['email'])->send(new ApprenantCredentialsMail($this->params));
        }
    }
}
