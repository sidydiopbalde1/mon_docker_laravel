<?php
namespace App\Http\Controllers;

use App\Services\AuthentificationFactory;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthentificationFactory $authFactory)
    {
        // Utiliser la factory pour obtenir le service d'authentification approprié
        $this->authService = $authFactory->make();
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $result = $this->authService->authenticate($credentials);
        return response()->json($result);
    }

    public function logout()
    {
        // Appeler la méthode logout() du service sélectionné
        $this->authService->logout();

        return response()->json(['message' => 'Logged out successfully']);
    }
}

