<?php

namespace App\Repositories;

use App\Models\Account;
use App\Models\Transfer;
use App\Repositories\Interfaces\TransferRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class TransferRepository implements TransferRepositoryInterface
{
    public function allForAccount(Account $account, array $filters = []): Collection
    {
        $query = Transfer::where(function ($q) use ($account) {
            $q->where('sender_account_id', $account->id)
                ->orWhere('receiver_account_id', $account->id);
        });

        if (!empty($filters['from'])) {
            $query->whereDate('created_at', '>=', $filters['from']);
        }

        if (!empty($filters['to'])) {
            $query->whereDate('created_at', '<=', $filters['to']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderByDesc('created_at')->get();
    }
}
