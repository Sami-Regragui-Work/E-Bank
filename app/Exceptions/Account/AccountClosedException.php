<?php

namespace App\Exceptions\Account;

use App\Exceptions\AppException;

class AccountClosedException extends AppException
{
    public function __construct()
    {
        parent::__construct(
            'This account is closed.',
            'ACCOUNT_CLOSED',
            403
        );
    }
}
