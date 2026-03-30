<?php

namespace App\Services;

use App\Exceptions\Account\{NonZeroBalanceException, PendingClosureConsentException, AccountNotActiveException, AccountNotBlockedException};
use App\Models\{Account, BlockedAccount, Role};
use Illuminate\Support\Facades\DB;

class AdminService
{
    public function blockAccount(Account $account, string $reason): BlockedAccount
    {
        if (!$account->isActive()) {
            throw new AccountNotActiveException();
        }

        return DB::transaction(function () use ($account, $reason) {
            $account->update(['status' => Account::STATUS_BLOCKED]);

            return BlockedAccount::updateOrCreate(
                ['account_id' => $account->id],
                ['admin_id' => auth('api')->id(), 'reason' => $reason, 'fee_failed' => false]
            );
        });
    }

    public function unblockAccount(Account $account): Account
    {
        if (!$account->isBlocked()) {
            throw new AccountNotBlockedException();
        }

        return DB::transaction(function () use ($account) {
            $account->update(['status' => Account::STATUS_ACTIVE]);
            $account->blockedAccount()->delete();

            return $account->fresh();
        });
    }

    public function closeAccount(Account $account): Account
    {
        if ($account->balance != 0) {
            throw new NonZeroBalanceException();
        }

        $owners = $account->users()->whereHas('role', fn($q) => $q->where('name', Role::OWNER))->get();
        $pendingCount = $owners->where('pivot.accept_closure', false)->count();

        if ($pendingCount > 0) {
            throw new PendingClosureConsentException();
        }

        return DB::transaction(function () use ($account) {
            $account->update(['status' => Account::STATUS_CLOSED]);
            return $account->fresh();
        });
    }
}
