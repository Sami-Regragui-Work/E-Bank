<?php

namespace App\Exceptions\Account;

use App\Exceptions\AppException;

class PendingClosureConsentException extends AppException
{
    public function __construct()
    {
        parent::__construct(
            'All account owners must consent before closing.',
            'PENDING_CLOSURE_CONSENT',
            409
        );
    }
}
