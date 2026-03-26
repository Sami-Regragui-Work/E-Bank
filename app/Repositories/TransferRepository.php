<?php

namespace App\Repositories;

use App\Models\Account;
use App\Models\Transfer;
use App\Repositories\Interfaces\TransferRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class TransferRepository implements TransferRepositoryInterface
{
    public function allForAccount(Account $account, ?string $status, ?Carbon $from, ?Carbon $to): Collection
    {
        $query = Transfer::where(function ($q) use ($account) {
            $q->where('sender_account_id', $account->id)
                ->orWhere('receiver_account_id', $account->id);
        });

        $query->whereDate('created_at', '>=', $from);

        $query->whereDate('created_at', '<=', $to);

        $query->where('status', $status);

        return $query->orderByDesc('created_at')->get();
    }
}
