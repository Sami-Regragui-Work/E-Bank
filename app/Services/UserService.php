<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function update(
        User $user,
        string $username,
        string $firstName,
        string $lastName,
        string $email,
        Carbon $birthdate,
    ): User {
        $user->update([
            'username' => $username,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'birthdate' => $birthdate,
        ]);

        return $user->fresh();
    }

    public function updatePassword(User $user, string $newPassword): void
    {
        $user->update([
            'password' => $newPassword,
        ]);
    }
}
