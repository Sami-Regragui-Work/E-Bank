<?php

namespace App\Services;

use App\Exceptions\Auth\InvalidCredentialsException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    public function __construct()
    {
        Auth::shouldUse('api');
    }

    public function register(
        string $username,
        string $firstName,
        string $lastName,
        string $email,
        string $password,
        string $birthdate
    ): array {
        $user = User::create([
            'username' => $username,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'password' => $password,
            'birthdate' => $birthdate,
        ]);

        Auth::login($user);
        $token = JWTAuth::fromUser($user);

        return $this->getAuthResponse($token, $user);
    }

    public function login(string $email, string $password): array
    {
        if (!$token = Auth::attempt(compact('email', 'password'))) {
            throw new InvalidCredentialsException();
        }

        return $this->getAuthResponse($token, Auth::user());
    }

    public function refresh(): array
    {
        $token = Auth::refresh();
        return [
            'token' => $token,
            'token_type' => 'bearer'
        ];
    }

    public function logout(): void
    {
        Auth::logout();
    }

    private function getAuthResponse(string $token, User $user): array
    {
        return [
            'user' => $user,
            'token' => $token,
            'token_type' => 'bearer',
        ];
    }
}
