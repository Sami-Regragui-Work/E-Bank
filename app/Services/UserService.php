<?php

namespace App\Services;

use App\Exceptions\Auth\InvalidCredentialsException;
use App\Models\User;
use Illuminate\Support\Facades\{Auth, Hash};

class UserService
{
    public function __construct()
    {
        Auth::shouldUse('api');
    }

    public function getProfile(): User
    {
        return Auth::user();
    }

    public function updateProfile(
        string $firstName,
        string $lastName,
        ?string $birthdate = null
    ): User {
        /**
         * @var User $user
         */
        $user = Auth::user();

        $user->update([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'birthdate' => $birthdate,
        ]);

        return $user->fresh();
    }

    public function changePassword(string $currentPassword, string $newPassword): void
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        if (!Hash::check($currentPassword, $user->password)) {
            throw new InvalidCredentialsException();
        }

        $user->update(['password' => $newPassword]);
    }
}
