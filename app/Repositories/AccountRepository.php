<?php

namespace App\Repositories;

use App\Models\Account;
use App\Models\Transfer;
use App\Models\User;
use App\Repositories\Interfaces\AccountRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class AccountRepository implements AccountRepositoryInterface
{
    public function allForUser(User $user): Collection
    {
        return Account::with(['type', 'blockedAccount'])
            ->whereHas('users', fn($q) => $q->where('users.id', $user->id))
            ->get();
    }

    public function findByRib(string $rib): ?Account
    {
        return Account::where('RIB', $rib)->first();
    }

    public function countMonthlyWithdrawals(Account $account): int
    {
        return $account->outgoingTransfers()
            ->where('status', Transfer::STATUS_COMPLETED)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
    }

    public function sumDailyTransfers(Account $account): float
    {
        return (float) $account->outgoingTransfers()
            ->where('status', Transfer::STATUS_COMPLETED)
            ->whereDate('created_at', today())
            ->sum('amount');
    }
}
