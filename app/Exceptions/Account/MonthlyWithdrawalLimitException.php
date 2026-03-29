<?php

namespace App\Exceptions\Account;

use App\Exceptions\AppException;

class MonthlyWithdrawalLimitException extends AppException
{
    public function __construct()
    {
        parent::__construct(
            'The monthly withdrawal limit has been reached.',
            'MONTHLY_WITHDRAWAL_LIMIT_REACHED',
            422
        );
    }
}
