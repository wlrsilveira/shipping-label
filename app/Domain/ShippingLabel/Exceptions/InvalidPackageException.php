<?php

namespace App\Domain\ShippingLabel\Exceptions;

class InvalidPackageException extends DomainException
{
    protected int $statusCode = 422;
    protected string $errorCode = 'INVALID_PACKAGE';

    public static function invalidDimensions(string $reason): self
    {
        return new self("Invalid package dimensions: {$reason}");
    }

    public static function invalidWeight(string $reason): self
    {
        return new self("Invalid package weight: {$reason}");
    }

    public static function exceedsMaximumSize(): self
    {
        return new self('Package exceeds maximum allowable size');
    }

    public static function exceedsMaximumWeight(): self
    {
        return new self('Package exceeds maximum allowable weight');
    }
}



