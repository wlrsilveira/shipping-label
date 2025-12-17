<?php

namespace App\Domain\User\Exceptions;

final class UserAlreadyExistsException extends DomainException
{
    protected int $statusCode = 409;
    protected string $errorCode = 'USER_ALREADY_EXISTS';

    public static function withEmail(string $email): self
    {
        return new self("User with this email already exists: {$email}");
    }
}

