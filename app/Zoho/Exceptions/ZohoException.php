<?php

namespace App\Zoho\Exceptions;

use Exception;

class ZohoException extends Exception
{
    public const TOKEN_EXPIRED = 'The access token expired';

    /**
     * Returns whether the exception was thrown because of an expired access token.
     */
    public function hasExpiredToken(): bool
    {
        return $this->getMessage() === self::TOKEN_EXPIRED;
    }
}
