<?php

namespace App\Services;

use App\Exceptions\Account\{MinorCannotCreateException, UnauthorizedAccountAccessException};
use App\Models\{Account, AccountUser, Role, Type, User};
use App\Repositories\AccountUserRepository;
use Illuminate\Support\Facades\{Auth, DB};

class AccountService
{
    public function __construct(private AccountUserRepository $accountUserRepo)
    {
    }

    public function createAccount(string $typeName, ?int $guardianId = null): Account
    {
        /**
         * @var User $owner
         */
        $owner = Auth::user();

        if ($owner->isMinor()) {
            throw new MinorCannotCreateException();
        }

        $type = Type::where('name', $typeName)->firstOrFail();

        if ($type->isMinor() && !$guardianId) {
            throw new MinorCannotCreateException();
        }

        return DB::transaction(function () use ($owner, $type, $guardianId) {
            $account = Account::create([
                'RIB' => $this->generateRIB(),
                'type_id' => $type->id,
                'daily_transaction_limit' => $type->default_daily_transaction_limit,
                'monthly_withdrawal_limit' => $type->default_monthly_withdrawal_limit,
                'balance' => 0.00,
            ]);

            $account->users()->attach($owner, ['role_id' => Role::OWNER]);

            if ($type->isMinor() && $guardianId) {
                $guardian = User::findOrFail($guardianId);
                if ($guardian->isMinor()) {
                    throw new MinorCannotCreateException();
                }
                $account->users()->attach($guardian, ['role_id' => Role::GUARDIAN]);
            }

            return $account->fresh(['type', 'users.role']);
        });
    }

    public function addCoOwner(Account $account, int $userId): AccountUser
    {
        $this->validateCoOwnerAddition($account, $userId);

        $user = User::findOrFail($userId);
        $account->users()->attach($user, ['role_id' => Role::OWNER]);

        return $account->users()->where('user_id', $userId)->first();
    }

    public function removeCoOwner(Account $account, int $userId): Account
    {
        $this->validateCoOwnerRemoval($account, $userId);

        $account->users()->detach($userId);
        return $account->fresh(['users.role']);
    }

    public function convertMinorToCurrent(Account $account): Account
    {
        $this->validateMinorConversion($account);

        $account->update(['type_id' => Type::CURRENT]);
        return $account->fresh(['type', 'users.role']);
    }

    public function requestClosure(Account $account): void
    {
        $user = Auth::user();
        $this->validateClosureRequest($account, $user);

        $account->users()->updateExistingPivot($user->id, ['accept_closure' => true]);
    }

    private function validateCoOwnerAddition(Account $account, int $userId): void
    {
        if ($account->type->isMinor()) {
            throw new UnauthorizedAccountAccessException();
        }

        if ($userId === Auth::id()) {
            throw new UnauthorizedAccountAccessException();
        }

        if ($account->users()->where('user_id', $userId)->exists()) {
            throw new UnauthorizedAccountAccessException();
        }
    }

    private function validateCoOwnerRemoval(Account $account, int $userId): void
    {
        if ($account->users()->whereHas('role', fn($q) => $q->where('name', Role::OWNER))->count() <= 1) {
            throw new UnauthorizedAccountAccessException();
        }
    }

    private function validateMinorConversion(Account $account): void
    {
        if (!$account->type->isMinor()) {
            throw new UnauthorizedAccountAccessException();
        }

        $minorUser = $account->users()->whereHas('role', fn($q) => $q->where('name', Role::MINOR))->first();
        if (!$minorUser || $minorUser->age < 18) {
            throw new UnauthorizedAccountAccessException();
        }

        $guardian = $account->users()->whereHas('role', fn($q) => $q->where('name', Role::GUARDIAN))->first();
        if (!$guardian->pivot->accept_closure) {
            throw new UnauthorizedAccountAccessException();
        }
    }

    private function validateClosureRequest(Account $account, User $user): void
    {
        if (!$this->accountUserRepo->isOwner($user, $account)) {
            throw new UnauthorizedAccountAccessException();
        }

        $accountUser = $account->users()->where('user_id', $user->id)->first();
        if ($accountUser->pivot->accept_closure) {
            throw new UnauthorizedAccountAccessException();
        }
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
