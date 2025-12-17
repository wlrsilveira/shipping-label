<?php

namespace App\Domain\ShippingLabel\ValueObjects;

enum DimensionUnit: string
{
    case INCH = 'in';
    case CENTIMETER = 'cm';
    case MILLIMETER = 'mm';

    public static function fromString(string $unit): self
    {
        $normalized = strtolower(trim($unit));
        
        return self::tryFrom($normalized) 
            ?? throw new \InvalidArgumentException("Invalid dimension unit: {$unit}. Allowed units: in, cm, mm");
    }

    public static function isValid(string $unit): bool
    {
        return self::tryFrom(strtolower(trim($unit))) !== null;
    }

    public function getFullName(): string
    {
        return match($this) {
            self::INCH => 'Inch',
            self::CENTIMETER => 'Centimeter',
            self::MILLIMETER => 'Millimeter',
        };
    }

    public function isMetric(): bool
    {
        return $this === self::CENTIMETER || $this === self::MILLIMETER;
    }

    public function isImperial(): bool
    {
        return $this === self::INCH;
    }
}

