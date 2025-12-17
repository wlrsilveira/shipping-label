<?php

namespace Tests\Unit\Domain\ShippingLabel\Factories;

use App\Domain\ShippingLabel\Factories\PackageFactory;
use App\Domain\ShippingLabel\ValueObjects\DimensionUnit;
use App\Domain\ShippingLabel\ValueObjects\Package;
use App\Domain\ShippingLabel\ValueObjects\WeightUnit;
use PHPUnit\Framework\TestCase;

class PackageFactoryTest extends TestCase
{
    public function test_can_create_package(): void
    {
        $package = PackageFactory::create(
            weight: 10.0,
            length: 12.0,
            width: 8.0,
            height: 6.0
        );

        $this->assertInstanceOf(Package::class, $package);
        $this->assertEquals(10.0, $package->getWeight());
        $this->assertEquals(12.0, $package->getLength());
        $this->assertEquals(8.0, $package->getWidth());
        $this->assertEquals(6.0, $package->getHeight());
        $this->assertEquals(WeightUnit::POUND, $package->getWeightUnit());
        $this->assertEquals(DimensionUnit::INCH, $package->getDimensionUnit());
    }

    public function test_can_create_package_with_custom_units(): void
    {
        $package = PackageFactory::create(
            weight: 5.0,
            length: 30.0,
            width: 20.0,
            height: 15.0,
            weightUnit: WeightUnit::KILOGRAM,
            dimensionUnit: DimensionUnit::CENTIMETER
        );

        $this->assertEquals(WeightUnit::KILOGRAM, $package->getWeightUnit());
        $this->assertEquals(DimensionUnit::CENTIMETER, $package->getDimensionUnit());
    }

    public function test_can_create_default_package(): void
    {
        $package = PackageFactory::makeDefault();

        $this->assertInstanceOf(Package::class, $package);
        $this->assertEquals(1.5, $package->getWeight());
        $this->assertEquals(10.0, $package->getLength());
        $this->assertEquals(8.0, $package->getWidth());
        $this->assertEquals(4.0, $package->getHeight());
    }

    public function test_can_create_small_package(): void
    {
        $package = PackageFactory::makeSmall();

        $this->assertInstanceOf(Package::class, $package);
        $this->assertEquals(0.5, $package->getWeight());
        $this->assertEquals(6.0, $package->getLength());
        $this->assertEquals(4.0, $package->getWidth());
        $this->assertEquals(2.0, $package->getHeight());
    }

    public function test_can_create_medium_package(): void
    {
        $package = PackageFactory::makeMedium();

        $this->assertInstanceOf(Package::class, $package);
        $this->assertEquals(5.0, $package->getWeight());
        $this->assertEquals(12.0, $package->getLength());
        $this->assertEquals(10.0, $package->getWidth());
        $this->assertEquals(8.0, $package->getHeight());
    }

    public function test_can_create_large_package(): void
    {
        $package = PackageFactory::makeLarge();

        $this->assertInstanceOf(Package::class, $package);
        $this->assertEquals(25.0, $package->getWeight());
        $this->assertEquals(24.0, $package->getLength());
        $this->assertEquals(18.0, $package->getWidth());
        $this->assertEquals(12.0, $package->getHeight());
    }

    public function test_can_create_oversized_package(): void
    {
        $package = PackageFactory::makeOversized();

        $this->assertInstanceOf(Package::class, $package);
        $this->assertEquals(50.0, $package->getWeight());
        $this->assertEquals(48.0, $package->getLength());
        $this->assertEquals(36.0, $package->getWidth());
        $this->assertEquals(24.0, $package->getHeight());
    }

    public function test_can_create_package_with_metric_units(): void
    {
        $package = PackageFactory::makeWithMetricUnits();

        $this->assertInstanceOf(Package::class, $package);
        $this->assertEquals(1.0, $package->getWeight());
        $this->assertEquals(25.0, $package->getLength());
        $this->assertEquals(20.0, $package->getWidth());
        $this->assertEquals(10.0, $package->getHeight());
        $this->assertEquals(WeightUnit::KILOGRAM, $package->getWeightUnit());
        $this->assertEquals(DimensionUnit::CENTIMETER, $package->getDimensionUnit());
    }

    public function test_can_create_from_array(): void
    {
        $data = [
            'weight' => 7.5,
            'length' => 15.0,
            'width' => 10.0,
            'height' => 5.0,
            'weight_unit' => 'kg',
            'dimension_unit' => 'cm',
        ];

        $package = PackageFactory::fromArray($data);

        $this->assertInstanceOf(Package::class, $package);
        $this->assertEquals(7.5, $package->getWeight());
        $this->assertEquals(15.0, $package->getLength());
        $this->assertEquals(WeightUnit::KILOGRAM, $package->getWeightUnit());
        $this->assertEquals(DimensionUnit::CENTIMETER, $package->getDimensionUnit());
    }
}
