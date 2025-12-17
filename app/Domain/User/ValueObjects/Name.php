<?php

namespace App\Domain\User\ValueObjects;

use InvalidArgumentException;

final readonly class Name
{
    public function __construct(
        private string $value
    ) {
        if (empty(trim($value))) {
            throw new InvalidArgumentException("Name cannot be empty");
        }

        if (strlen($value) > 255) {
            throw new InvalidArgumentException("Name cannot exceed 255 characters");
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}

