<?php

namespace App\Services;

use App\Exceptions\Account\{DailyLimitExceededException, InsufficientBalanceException, MonthlyWithdrawalLimitException, GuardianRequiredException};
use App\Exceptions\Transfer\SameAccountTransferException;
use App\Models\{Account, Transfer, User};
use App\Repositories\AccountUserRepository;
use Illuminate\Support\Facades\{Auth, DB};

class TransferService
{
    public function __construct(private AccountUserRepository $accountUserRepo)
    {
        Auth::shouldUse('api');
    }
    public function initiateTransfer(int $fromAccountId, int $receiverAccountId, float $amount): Transfer
    {
        $sender = Auth::user();
        $fromAccount = Account::forUser($sender)->active()->findOrFail($fromAccountId);
        $this->validateTransfer($fromAccount, $receiverAccountId, $amount, $sender);

        $receiverAccount = Account::active()->findOrFail($receiverAccountId);

        return DB::transaction(function () use ($fromAccount, $receiverAccount, $amount, $sender) {
            $transfer = Transfer::create([
                'sender_id' => $sender->id,
                'sender_account_id' => $fromAccount->id,
                'receiver_account_id' => $receiverAccount->id,
                'amount' => $amount,
                'status' => Transfer::STATUS_PENDING,
            ]);

            $this->completeTransfer($transfer);

            return $transfer->fresh(['senderAccount', 'receiverAccount']);
        });
    }

    private function validateTransfer(Account $fromAccount, int $receiverAccountId, float $amount, User $sender): void
    {
        if ($fromAccount->id === $receiverAccountId) {
            throw new SameAccountTransferException();
        }

        $todayTransfers = $fromAccount->outgoingTransfers()
            ->whereDate('created_at', today())
            ->where('status', Transfer::STATUS_COMPLETED)
            ->sum('amount');

        $todayLimit = $fromAccount->daily_transaction_limit;
        if (($todayTransfers + $amount) > $todayLimit) {
            throw new DailyLimitExceededException();
        }

        $overdraft = $fromAccount->type->overdraft_limit ?? 0;
        if (($fromAccount->balance - $amount) < -$overdraft) {
            throw new InsufficientBalanceException();
        }

        if ($fromAccount->isMinor() && !$this->accountUserRepo->isGuardian($sender, $fromAccount)) {
            throw new GuardianRequiredException();
        }

        $monthlyWithdrawals = $fromAccount->outgoingTransfers()
            ->whereYear('created_at', today()->year)
            ->whereMonth('created_at', today()->month)
            ->where('status', Transfer::STATUS_COMPLETED)
            ->count();

        $monthlyLimit = $fromAccount->type->default_monthly_withdrawal_limit;
        if ($monthlyLimit > 0 && $monthlyWithdrawals >= $monthlyLimit) {
            throw new MonthlyWithdrawalLimitException();
        }
    }

    private function completeTransfer(Transfer $transfer): void
    {
        $transfer->senderAccount->decrement('balance', (float) $transfer->amount);
        $transfer->receiverAccount->increment('balance', (float) $transfer->amount);
        $transfer->update(['status' => Transfer::STATUS_COMPLETED]);
    }
}
