<?php

namespace Tests\Unit\Domain\ShippingLabel\ValueObjects;

use App\Domain\ShippingLabel\ValueObjects\DimensionUnit;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class DimensionUnitTest extends TestCase
{
    public function test_can_create_valid_dimension_unit(): void
    {
        $unit = DimensionUnit::INCH;

        $this->assertEquals('in', $unit->value);
    }

    public function test_can_create_from_string(): void
    {
        $unit = DimensionUnit::fromString('in');

        $this->assertEquals(DimensionUnit::INCH, $unit);
    }

    public function test_can_create_from_uppercase_string(): void
    {
        $unit = DimensionUnit::fromString('IN');

        $this->assertEquals(DimensionUnit::INCH, $unit);
    }

    public function test_can_create_from_string_with_spaces(): void
    {
        $unit = DimensionUnit::fromString('  cm  ');

        $this->assertEquals(DimensionUnit::CENTIMETER, $unit);
    }

    public function test_throws_exception_for_invalid_unit(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid dimension unit: invalid');

        DimensionUnit::fromString('invalid');
    }

    public function test_can_validate_unit(): void
    {
        $this->assertTrue(DimensionUnit::isValid('in'));
        $this->assertTrue(DimensionUnit::isValid('cm'));
        $this->assertFalse(DimensionUnit::isValid('invalid'));
    }

    public function test_can_get_full_name(): void
    {
        $this->assertEquals('Inch', DimensionUnit::INCH->getFullName());
        $this->assertEquals('Centimeter', DimensionUnit::CENTIMETER->getFullName());
        $this->assertEquals('Millimeter', DimensionUnit::MILLIMETER->getFullName());
    }

    public function test_can_check_if_is_metric(): void
    {
        $this->assertTrue(DimensionUnit::CENTIMETER->isMetric());
        $this->assertTrue(DimensionUnit::MILLIMETER->isMetric());
        $this->assertFalse(DimensionUnit::INCH->isMetric());
    }

    public function test_can_check_if_is_imperial(): void
    {
        $this->assertTrue(DimensionUnit::INCH->isImperial());
        $this->assertFalse(DimensionUnit::CENTIMETER->isImperial());
        $this->assertFalse(DimensionUnit::MILLIMETER->isImperial());
    }
}
