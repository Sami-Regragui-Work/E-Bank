<?php

namespace App\Repositories\Interfaces;

use App\Models\Account;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

interface TransferRepositoryInterface
{
    public function allForAccount(Account $account, ?string $status, ?Carbon $from, ?Carbon $to): Collection;
}
