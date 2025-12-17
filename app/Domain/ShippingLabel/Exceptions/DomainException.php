<?php

namespace App\Domain\ShippingLabel\Exceptions;

use DomainException as BaseDomainException;

abstract class DomainException extends BaseDomainException
{
    protected int $statusCode = 422;
    protected string $errorCode = 'SHIPPING_LABEL_DOMAIN_ERROR';

    public function __construct(
        string $message = '',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }
}


