<?php

namespace App\Domain\ShippingLabel\Exceptions;

class UnauthorizedAccessException extends DomainException
{
    protected int $statusCode = 403;
    protected string $errorCode = 'UNAUTHORIZED_ACCESS';

    public static function toShippingLabel(int $labelId, int $userId): self
    {
        return new self("User {$userId} is not authorized to access shipping label {$labelId}");
    }

    public static function default(): self
    {
        return new self('You are not authorized to access this shipping label');
    }
}



