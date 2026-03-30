<?php

namespace App\Exceptions\Account;

use App\Exceptions\AppException;

class AccountNotActiveException extends AppException
{
    public function __construct()
    {
        parent::__construct(
            'Account is not active.',
            'ACCOUNT_NOT_ACTIVE',
            403
        );
    }
}
