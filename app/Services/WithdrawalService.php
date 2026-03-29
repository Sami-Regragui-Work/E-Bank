<?php

namespace App\Services;

use App\Exceptions\Account\{DailyLimitExceededException, GuardianRequiredException, InsufficientBalanceException, MonthlyWithdrawalLimitException};
use App\Models\{Account, Withdrawal, Transfer, User};
use App\Repositories\AccountUserRepository;
use Illuminate\Support\Facades\{Auth, DB};

class WithdrawalService
{
    public function __construct(private AccountUserRepository $accountUserRepo)
    {
    }

    public function makeWithdrawal(int $accountId, float $amount): Withdrawal
    {
        $withdrawer = Auth::user();
        $account = Account::forUser($withdrawer)->active()->findOrFail($accountId);

        $this->validateWithdrawal($account, $amount, $withdrawer);

        return DB::transaction(function () use ($account, $amount, $withdrawer) {
            $withdrawal = Withdrawal::create([
                'user_id' => $withdrawer->id,
                'account_id' => $account->id,
                'amount' => $amount,
                'is_fee' => false,
            ]);

            $account->decrement('balance', $amount);

            return $withdrawal->fresh(['account']);
        });
    }

    private function validateWithdrawal(Account $account, float $amount, User $withdrawer): void
    {
        if ($account->isMinor() && !$this->accountUserRepo->isGuardian($withdrawer, $account)) {
            throw new GuardianRequiredException();
        }

        $todayTransactions = $account->outgoingTransfers()
            ->whereDate('created_at', today())
            ->where('status', Transfer::STATUS_COMPLETED)
            ->sum('amount');

        $todayWithdrawals = $account->withdrawals()
            ->whereDate('created_at', today())
            ->sum('amount');

        $todayTotal = $todayTransactions + $todayWithdrawals;
        if (($todayTotal + $amount) > $account->daily_transaction_limit) {
            throw new DailyLimitExceededException();
        }

        $monthlyWithdrawals = $account->withdrawals()
            ->whereYear('created_at', today()->year)
            ->whereMonth('created_at', today()->month)
            ->count();

        $monthlyLimit = $account->type->default_monthly_withdrawal_limit;
        if ($monthlyLimit > 0 && $monthlyWithdrawals >= $monthlyLimit) {
            throw new MonthlyWithdrawalLimitException();
        }

        $overdraft = $account->type->overdraft_limit ?? 0;
        if (($account->balance - $amount) < -$overdraft) {
            throw new InsufficientBalanceException();
        }
    }
}
