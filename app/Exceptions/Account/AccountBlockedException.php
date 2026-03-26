<?php

namespace App\Exceptions\Account;

use App\Exceptions\AppException;

class AccountBlockedException extends AppException
{
    public function __construct()
    {
        parent::__construct('This account is blocked and cannot perform operations.', 403);
    }
}
