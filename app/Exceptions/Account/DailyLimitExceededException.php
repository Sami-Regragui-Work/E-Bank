<?php

namespace App\Exceptions\Account;

use Exception;

class DailyLimitExceededException extends Exception
{
    public function __construct()
    {
        parent::__construct(
            'The daily transaction limit has been exceeded.',
            'DAILY_LIMIT_EXCEEDED',
            409
        );
    }
}
