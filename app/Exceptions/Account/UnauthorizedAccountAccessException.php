<?php

namespace App\Exceptions\Account;

use App\Exceptions\AppException;

class UnauthorizedAccountAccessException extends AppException
{
    public function __construct()
    {
        parent::__construct(
            'You are not authorized to perform this action.',
            'UNAUTHORIZED_ACCESS',
            403
        );
    }
}
