<?php

namespace App\Repositories;

use App\Models\{Account, AccountUser, User, Role};

class AccountUserRepository
{
    public function getRoleNameFor(User $user, Account $account): ?string
    {
        return AccountUser::where('user_id', $user->id)
            ->where('account_id', $account->id)
            ->with('role')
            ->first()?->role?->name;
    }

    public function isGuardian(User $user, Account $account): bool
    {
        return $this->getRoleNameFor($user, $account) === Role::GUARDIAN;
    }

    public function isOwner(User $user, Account $account): bool
    {
        return $this->getRoleNameFor($user, $account) === Role::OWNER;
    }

    public function isMinor(User $user, Account $account): bool
    {
        return $this->getRoleNameFor($user, $account) === Role::MINOR;
    }
}
