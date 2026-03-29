<?php

namespace App\Exceptions\Account;

use App\Exceptions\AppException;

class DailyLimitExceededException extends AppException
{
    public function __construct()
    {
        parent::__construct(
            'The daily transaction limit has been exceeded.',
            'DAILY_LIMIT_EXCEEDED',
            422
        );
    }
}
