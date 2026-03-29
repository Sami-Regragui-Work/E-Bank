<?php

namespace App\Services;

use App\Exceptions\Account\MinorCannotCreateException;
use App\Models\{Account, AccountUser, Role, Type, User};
use Illuminate\Support\Facades\Auth;

class AccountService
{
    public function createAccount(string $typeName): Account
    {
        /**
         * @var User $owner
         */
        $owner = Auth::user();
        if ($owner->isMinor()) {
            throw new MinorCannotCreateException();
        }

        $type = Type::where('name', $typeName)->firstOrFail();
        $rib = $this->generateRIB();

        $account = Account::create([
            'RIB' => $rib,
            'type_id' => $type->id,
            'daily_transaction_limit' => $type->default_daily_transaction_limit,
            'monthly_withdrawal_limit' => $type->default_monthly_withdrawal_limit,
            'balance' => 0.00,
        ]);

        $ownerRole = Role::where('name', Role::OWNER)->firstOrFail();
        $account->users()->attach($owner, ['role_id' => $ownerRole->id]);

        return $account->fresh(['type', 'users.role']);
    }

    public function addCoOwner(Account $account, int $userId): AccountUser
    {
        $user = User::findOrFail($userId);
        $ownerRole = Role::where('name', Role::OWNER)->firstOrFail();
        $account->users()->attach($user, ['role_id' => $ownerRole->id]);

        return $account->users()->where('user_id', $userId)->first();
    }

    public function requestClosure(Account $account): void
    {
        $account->users()->updateExistingPivot(Auth::id(), [
            'accept_closure' => true
        ]);
    }

    private function generateRIB(): string
    {
        $bankCode = '170';
        do {
            $agencyCode = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            $accountNumber = str_pad(rand(1, 9999999999999999), 16, '0', STR_PAD_LEFT);
            $key = str_pad(rand(1, 99), 2, '0', STR_PAD_LEFT);
            $rib = "{$bankCode}{$agencyCode}{$accountNumber}{$key}";
        } while (Account::where('RIB', $rib)->exists());

        return $rib;
    }
}
