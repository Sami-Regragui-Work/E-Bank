<?php

namespace App\Exceptions\Auth;

use App\Exceptions\AppException;

class InvalidCredentialsException extends AppException
{
    public function __construct()
    {
        parent::__construct(
            'Invalid credentials.',
            'INVALID_CREDENTIALS',
            401
        );
    }
}
