<?php

namespace App\Services;

use App\Exceptions\Account\DailyLimitExceededException;
use App\Models\{Account, Deposit};
use Illuminate\Support\Facades\{Auth, DB};

class DepositService
{
    public function makeDeposit(int $accountId, float $amount): Deposit
    {
        $depositor = Auth::user();
        $account = Account::active()->findOrFail($accountId);

        return DB::transaction(function () use ($account, $amount, $depositor) {
            $todayDeposits = $account->deposits()
                ->whereDate('created_at', today())
                ->sum('amount');

            if (($todayDeposits + $amount) > $account->daily_transaction_limit) {
                throw new DailyLimitExceededException();
            }

            $deposit = Deposit::create([
                'sender_id' => $depositor?->id,
                'account_id' => $account->id,
                'amount' => $amount,
                'is_interest' => false,
            ]);

            $account->increment('balance', $amount);

            return $deposit->fresh(['account']);
        });
    }
}
