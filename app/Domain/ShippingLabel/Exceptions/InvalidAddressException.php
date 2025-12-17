<?php

namespace App\Domain\ShippingLabel\Exceptions;

class InvalidAddressException extends DomainException
{
    protected int $statusCode = 422;
    protected string $errorCode = 'INVALID_ADDRESS';

    public static function onlyUSAddressesAllowed(): self
    {
        return new self('Only United States addresses are allowed');
    }

    public static function invalidFormat(string $reason): self
    {
        return new self("Invalid address format: {$reason}");
    }

    public static function missingRequiredField(string $field): self
    {
        return new self("Required address field missing: {$field}");
    }
}


