<?php

namespace App\Exceptions\Account;

use App\Exceptions\AppException;

class MinorCannotCreateException extends AppException
{
    public function __construct()
    {
        parent::__construct(
            'Minors cannot create accounts.',
            'MINOR_CANNOT_CREATE',
            422
        );
    }
}