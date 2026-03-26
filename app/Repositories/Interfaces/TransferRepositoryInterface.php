<?php

namespace App;

use App\Models\Account;
use Illuminate\Database\Eloquent\Collection;

interface TransferRepositoryInterface
{
    public function allForAccount(Account $account, array $filters = []): Collection;
}
