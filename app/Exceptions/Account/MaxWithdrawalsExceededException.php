<?php

namespace App\Exceptions\Account;

use App\Exceptions\AppException;

class MaxWithdrawalsExceededException extends AppException
{
    public function __construct()
    {
        parent::__construct(
            'Maximum monthly withdrawals exceeded.',
            'MAX_WITHDRAWALS_EXCEEDED',
            422
        );
    }
}