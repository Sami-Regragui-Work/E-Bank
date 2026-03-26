<?php

namespace App\Repositories\Interfaces;

use App\Models\Account;
use Illuminate\Database\Eloquent\Collection;

interface TransferRepositoryInterface
{
    public function allForAccount(Account $account, array $filters = []): Collection;
}
