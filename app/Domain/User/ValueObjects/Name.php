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

    public function equals(Name $other): bool
    {
        return $this->value === $other->value;
    }

    public function toArray(): array
    {
        return ['name' => $this->value];
    }

    public static function fromArray(array $data): self
    {
        return new self($data['name']);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}

