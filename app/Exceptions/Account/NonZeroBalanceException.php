<?php

namespace App\Exceptions\Account;

use App\Exceptions\AppException;

class NonZeroBalanceException extends AppException
{
    public function __construct()
    {
        parent::__construct(
            'Account balance must be zero before closing.',
            'NON_ZERO_BALANCE',
            422
        );
    }
}
