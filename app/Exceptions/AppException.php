<?php

namespace App\Exceptions;

use Exception;

class AppException extends Exception
{
    public function __construct(
        string $message,
        private readonly string $errorCode,
        int $httpStatus
    ) {
        parent::__construct($message, $httpStatus);
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }
}
