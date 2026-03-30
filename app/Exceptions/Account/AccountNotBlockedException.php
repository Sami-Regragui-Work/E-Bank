<?php

namespace App\Exceptions\Account;

use App\Exceptions\AppException;

class AccountNotBlockedException extends AppException
{
    public function __construct()
    {
        parent::__construct(
            'Account is not blocked.',
            'ACCOUNT_NOT_BLOCKED',
            403
        );
    }
}
