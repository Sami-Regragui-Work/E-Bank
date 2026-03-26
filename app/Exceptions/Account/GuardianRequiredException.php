<?php

namespace App\Exceptions\Account;

use App\Exceptions\AppException;

class GuardianRequiredException extends AppException
{
    public function __construct()
    {
        parent::__construct(
            'Only the guardian can perform this action on a minor account.',
            'GUARDIAN_REQUIRED',
            403
        );
    }
}
