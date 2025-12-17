<?php

namespace App\Domain\User\Exceptions;

final class InvalidCredentialsException extends DomainException
{
    protected int $statusCode = 401;
    protected string $errorCode = 'INVALID_CREDENTIALS';

    public function __construct(string $message = 'Invalid credentials')
    {
        parent::__construct($message);
    }
}

