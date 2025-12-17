<?php

namespace App\Domain\ShippingLabel\ValueObjects;

enum Country: string
{
    case UNITED_STATES = 'US';

    public static function fromString(string $code): self
    {
        $normalized = strtoupper(trim($code));

        return self::tryFrom($normalized)
            ?? throw new \InvalidArgumentException("Only United States addresses are allowed. Provided: {$code}");
    }

    public static function isValid(string $code): bool
    {
        return self::tryFrom(strtoupper(trim($code))) !== null;
    }

    public function getFullName(): string
    {
        return match($this) {
            self::UNITED_STATES => 'United States',
        };
    }
}



