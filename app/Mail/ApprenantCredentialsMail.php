<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ApprenantCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $data;

    /**
     * ApprenantCredentialsMail constructor.
     * @param array $data Tableau d'arguments contenant les informations de l'apprenant
     */
    public function __construct(array $data)
    {
        // Stocker les données dans une propriété
        $this->data = $data;
    }

    public function build()
    {
        // Vérification du QR code avant envoi
        if (empty($this->data['qrcode'])) {
            Log::error("QR code non disponible pour : {$this->data['email']}");
        }

        // Générer l'e-mail avec les données fournies
        return $this->subject('Vos informations de connexion')
                    ->view('emails.relance_app')
                    ->with([
                        'email' => $this->data['email'] ?? 'Non spécifié',
                        'password' => $this->data['password'] ?? 'Non spécifié',
                        'matricule' => $this->data['matricule'] ?? 'Non spécifié',
                        'loginLink' => $this->data['loginLink'] ?? route('login'),
                        'qrcode' => $this->data['qrcode'] ?? null,
                    ])
                    // Attacher le QR code si disponible
                    ->attach($this->data['qrcode'] ?? '', [
                        'as' => 'qrcode.png',
                        'mime' => 'image/png',
                    ]);
    }
}
