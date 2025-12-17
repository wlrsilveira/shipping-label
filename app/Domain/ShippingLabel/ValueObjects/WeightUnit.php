<?php

namespace App\Domain\ShippingLabel\ValueObjects;

enum WeightUnit: string
{
    case POUND = 'lb';
    case OUNCE = 'oz';
    case KILOGRAM = 'kg';
    case GRAM = 'g';

    public static function fromString(string $unit): self
    {
        $normalized = strtolower(trim($unit));

        return self::tryFrom($normalized)
            ?? throw new \InvalidArgumentException("Invalid weight unit: {$unit}. Allowed units: lb, oz, kg, g");
    }

    public static function isValid(string $unit): bool
    {
        return self::tryFrom(strtolower(trim($unit))) !== null;
    }

    public function getFullName(): string
    {
        return match($this) {
            self::POUND => 'Pound',
            self::OUNCE => 'Ounce',
            self::KILOGRAM => 'Kilogram',
            self::GRAM => 'Gram',
        };
    }

    public function isMetric(): bool
    {
        return $this === self::KILOGRAM || $this === self::GRAM;
    }

    public function isImperial(): bool
    {
        return $this === self::POUND || $this === self::OUNCE;
    }
}



