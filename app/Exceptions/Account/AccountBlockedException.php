<?php

namespace App\Exceptions\Account;

use App\Exceptions\AppException;

class AccountBlockedException extends AppException
{
    public function __construct()
    {
        parent::__construct(
            'This account is blocked.',
            'ACCOUNT_BLOCKED',
            403
        );
    }
}
