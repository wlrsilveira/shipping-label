<?php

namespace App\Domain\ShippingLabel\Factories;

use App\Domain\ShippingLabel\ValueObjects\Package;
use App\Domain\ShippingLabel\ValueObjects\WeightUnit;
use App\Domain\ShippingLabel\ValueObjects\DimensionUnit;

class PackageFactory
{
    public static function create(
        float $weight,
        float $length,
        float $width,
        float $height,
        WeightUnit $weightUnit = WeightUnit::POUND,
        DimensionUnit $dimensionUnit = DimensionUnit::INCH,
    ): Package {
        return new Package(
            weight: $weight,
            length: $length,
            width: $width,
            height: $height,
            weightUnit: $weightUnit,
            dimensionUnit: $dimensionUnit,
        );
    }

    public static function makeDefault(): Package
    {
        return self::create(
            weight: 1.5,
            length: 10.0,
            width: 8.0,
            height: 4.0
        );
    }

    public static function makeSmall(): Package
    {
        return self::create(
            weight: 0.5,
            length: 6.0,
            width: 4.0,
            height: 2.0
        );
    }

    public static function makeMedium(): Package
    {
        return self::create(
            weight: 5.0,
            length: 12.0,
            width: 10.0,
            height: 8.0
        );
    }

    public static function makeLarge(): Package
    {
        return self::create(
            weight: 25.0,
            length: 24.0,
            width: 18.0,
            height: 12.0
        );
    }

    public static function makeOversized(): Package
    {
        return self::create(
            weight: 50.0,
            length: 48.0,
            width: 36.0,
            height: 24.0
        );
    }

    public static function makeWithMetricUnits(): Package
    {
        return self::create(
            weight: 1.0,
            length: 25.0,
            width: 20.0,
            height: 10.0,
            weightUnit: WeightUnit::KILOGRAM,
            dimensionUnit: DimensionUnit::CENTIMETER,
        );
    }

    public static function fromArray(array $data): Package
    {
        return Package::fromArray($data);
    }
}

