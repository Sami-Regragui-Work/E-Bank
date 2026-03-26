<?php

namespace App\Exceptions\Account;

use App\Exceptions\AppException;

class InsufficientBalanceException extends AppException
{
    public function __construct()
    {
        parent::__construct(
            'Insufficient balance to complete this operation.',
            'INSUFFICIENT_BALANCE',
            409
        );
    }
}
