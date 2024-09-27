<?php
namespace App\Services;

interface AuthentificationServiceInterface
{
    public function authenticate(array $credentials);
    public function logout();
}
