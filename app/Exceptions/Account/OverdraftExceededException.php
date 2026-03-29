<?php

namespace App\Exceptions\Account;

use App\Exceptions\AppException;

class OverdraftExceededException extends AppException
{
    public function __construct()
    {
        parent::__construct(
            'Transaction exceeds overdraft limit.',
            'OVERDRAFT_EXCEEDED',
            422
        );
    }
}