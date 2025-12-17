<?php

namespace Tests\Unit\Domain\ShippingLabel\ValueObjects;

use App\Domain\ShippingLabel\ValueObjects\WeightUnit;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class WeightUnitTest extends TestCase
{
    public function test_can_create_valid_weight_unit(): void
    {
        $unit = WeightUnit::POUND;

        $this->assertEquals('lb', $unit->value);
    }

    public function test_can_create_from_string(): void
    {
        $unit = WeightUnit::fromString('lb');

        $this->assertEquals(WeightUnit::POUND, $unit);
    }

    public function test_can_create_from_uppercase_string(): void
    {
        $unit = WeightUnit::fromString('LB');

        $this->assertEquals(WeightUnit::POUND, $unit);
    }

    public function test_can_create_from_string_with_spaces(): void
    {
        $unit = WeightUnit::fromString('  kg  ');

        $this->assertEquals(WeightUnit::KILOGRAM, $unit);
    }

    public function test_throws_exception_for_invalid_unit(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid weight unit: invalid');

        WeightUnit::fromString('invalid');
    }

    public function test_can_validate_unit(): void
    {
        $this->assertTrue(WeightUnit::isValid('lb'));
        $this->assertTrue(WeightUnit::isValid('kg'));
        $this->assertFalse(WeightUnit::isValid('invalid'));
    }

    public function test_can_get_full_name(): void
    {
        $this->assertEquals('Pound', WeightUnit::POUND->getFullName());
        $this->assertEquals('Ounce', WeightUnit::OUNCE->getFullName());
        $this->assertEquals('Kilogram', WeightUnit::KILOGRAM->getFullName());
        $this->assertEquals('Gram', WeightUnit::GRAM->getFullName());
    }

    public function test_can_check_if_is_metric(): void
    {
        $this->assertTrue(WeightUnit::KILOGRAM->isMetric());
        $this->assertTrue(WeightUnit::GRAM->isMetric());
        $this->assertFalse(WeightUnit::POUND->isMetric());
        $this->assertFalse(WeightUnit::OUNCE->isMetric());
    }

    public function test_can_check_if_is_imperial(): void
    {
        $this->assertTrue(WeightUnit::POUND->isImperial());
        $this->assertTrue(WeightUnit::OUNCE->isImperial());
        $this->assertFalse(WeightUnit::KILOGRAM->isImperial());
        $this->assertFalse(WeightUnit::GRAM->isImperial());
    }
}
