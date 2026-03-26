<?php

namespace App\Exceptions\Transfer;

use App\Exceptions\AppException;

class SameAccountTransferException extends AppException
{
    public function __construct()
    {
        parent::__construct(
            'Cannot transfer to the same account.',
            'SAME_ACCOUNT_TRANSFER',
            422
        );
    }
}
