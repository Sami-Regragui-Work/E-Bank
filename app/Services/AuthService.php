<?php

namespace App\Services;

use App\Exceptions\Auth\InvalidCredentialsException;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
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
        Carbon $birthdate,
    ): array {
        $user = User::create([
            'username' => $username,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'password' => $password,
            'birthdate' => $birthdate,
        ]);

        // $token = Auth::attempt(compact('email', 'password'));
        $token = JWTAuth::fromUser($user);
        Auth::login($user);

        return $this->tokenPayload($token, $user);
    }

    /**
     * @throws InvalidCredentialsException
     */
    public function login(string $email, string $password): array
    {
        if (!$token = Auth::attempt(compact('email', 'password'))) {
            throw new InvalidCredentialsException();
        }

        $user = Auth::user();

        return $this->tokenPayload($token, $user);
    }

    /**
     * @throws InvalidCredentialsException
     */
    public function refresh(): array
    {
        try {
            $token = Auth::refresh();
        } catch (TokenExpiredException | JWTException $e) {
            throw new InvalidCredentialsException();
        }

        return $this->tokenPayload($token, Auth::user());
    }

    public function logout(): void
    {
        Auth::logout();
    }

    private function tokenPayload(string $token, User $user): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
            'user' => $user,
        ];
    }
}
