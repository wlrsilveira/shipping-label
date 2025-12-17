<?php

namespace App\Domain\User\Exceptions;

final class UserNotFoundException extends DomainException
{
    protected int $statusCode = 404;
    protected string $errorCode = 'USER_NOT_FOUND';

    public static function withId(int $id): self
    {
        return new self("User not found with ID: {$id}");
    }

    public static function withEmail(string $email): self
    {
        return new self("User not found with email: {$email}");
    }
}

