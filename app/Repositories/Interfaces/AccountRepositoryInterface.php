<?php

namespace App\Repositories\Interfaces;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface AccountRepositoryInterface
{
    public function allForUser(User $user): Collection;

    public function findByRib(string $rib): ?Account;

    public function countMonthlyWithdrawals(Account $account): int;

    public function sumDailyTransfers(Account $account): float;
}
