<?php

namespace Tests\Unit\Domain\ShippingLabel\ValueObjects;

use App\Domain\ShippingLabel\ValueObjects\DimensionUnit;
use App\Domain\ShippingLabel\ValueObjects\Package;
use App\Domain\ShippingLabel\ValueObjects\WeightUnit;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class PackageTest extends TestCase
{
    public function test_can_create_valid_package(): void
    {
        $package = new Package(
            weight: 10.5,
            length: 12.0,
            width: 8.0,
            height: 6.0
        );

        $this->assertEquals(10.5, $package->getWeight());
        $this->assertEquals(12.0, $package->getLength());
        $this->assertEquals(8.0, $package->getWidth());
        $this->assertEquals(6.0, $package->getHeight());
        $this->assertEquals(WeightUnit::POUND, $package->getWeightUnit());
        $this->assertEquals(DimensionUnit::INCH, $package->getDimensionUnit());
    }

    public function test_can_create_package_with_custom_units(): void
    {
        $package = new Package(
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

    public function test_throws_exception_for_weight_too_low(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Weight must be at least');

        new Package(
            weight: 0.0,
            length: 12.0,
            width: 8.0,
            height: 6.0
        );
    }

    public function test_throws_exception_for_weight_too_high(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Weight cannot exceed');

        new Package(
            weight: 200.0,
            length: 12.0,
            width: 8.0,
            height: 6.0
        );
    }

    public function test_throws_exception_for_length_too_low(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Length must be at least');

        new Package(
            weight: 10.0,
            length: 0.0,
            width: 8.0,
            height: 6.0
        );
    }

    public function test_throws_exception_for_width_too_low(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Width must be at least');

        new Package(
            weight: 10.0,
            length: 12.0,
            width: 0.0,
            height: 6.0
        );
    }

    public function test_throws_exception_for_height_too_low(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Height must be at least');

        new Package(
            weight: 10.0,
            length: 12.0,
            width: 8.0,
            height: 0.0
        );
    }

    public function test_throws_exception_for_dimension_too_high(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('cannot exceed');

        new Package(
            weight: 10.0,
            length: 200.0,
            width: 8.0,
            height: 6.0
        );
    }

    public function test_can_calculate_volume(): void
    {
        $package = new Package(
            weight: 10.0,
            length: 12.0,
            width: 8.0,
            height: 6.0
        );

        $this->assertEquals(576.0, $package->getVolume());
    }

    public function test_can_get_unit_values(): void
    {
        $package = new Package(
            weight: 10.0,
            length: 12.0,
            width: 8.0,
            height: 6.0,
            weightUnit: WeightUnit::KILOGRAM,
            dimensionUnit: DimensionUnit::CENTIMETER
        );

        $this->assertEquals('kg', $package->getWeightUnitValue());
        $this->assertEquals('cm', $package->getDimensionUnitValue());
    }

    public function test_can_convert_to_array(): void
    {
        $package = new Package(
            weight: 10.5,
            length: 12.0,
            width: 8.0,
            height: 6.0
        );

        $expected = [
            'weight' => 10.5,
            'length' => 12.0,
            'width' => 8.0,
            'height' => 6.0,
            'weight_unit' => 'lb',
            'dimension_unit' => 'in',
        ];

        $this->assertEquals($expected, $package->toArray());
    }

    public function test_can_create_from_array(): void
    {
        $data = [
            'weight' => 10.5,
            'length' => 12.0,
            'width' => 8.0,
            'height' => 6.0,
            'weight_unit' => 'kg',
            'dimension_unit' => 'cm',
        ];

        $package = Package::fromArray($data);

        $this->assertEquals(10.5, $package->getWeight());
        $this->assertEquals(12.0, $package->getLength());
        $this->assertEquals(WeightUnit::KILOGRAM, $package->getWeightUnit());
        $this->assertEquals(DimensionUnit::CENTIMETER, $package->getDimensionUnit());
    }

    public function test_can_compare_equal_packages(): void
    {
        $package1 = new Package(10.0, 12.0, 8.0, 6.0);
        $package2 = new Package(10.0, 12.0, 8.0, 6.0);

        $this->assertTrue($package1->equals($package2));
    }

    public function test_can_compare_different_packages(): void
    {
        $package1 = new Package(10.0, 12.0, 8.0, 6.0);
        $package2 = new Package(11.0, 12.0, 8.0, 6.0);

        $this->assertFalse($package1->equals($package2));
    }
}
