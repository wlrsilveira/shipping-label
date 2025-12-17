<?php

namespace Tests\Unit\Domain\ShippingLabel\ValueObjects;

use App\Domain\ShippingLabel\ValueObjects\Country;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CountryTest extends TestCase
{
    public function test_can_create_valid_country(): void
    {
        $country = Country::UNITED_STATES;

        $this->assertEquals('US', $country->value);
    }

    public function test_can_create_from_string(): void
    {
        $country = Country::fromString('US');

        $this->assertEquals(Country::UNITED_STATES, $country);
    }

    public function test_can_create_from_lowercase_string(): void
    {
        $country = Country::fromString('us');

        $this->assertEquals(Country::UNITED_STATES, $country);
    }

    public function test_can_create_from_string_with_spaces(): void
    {
        $country = Country::fromString('  US  ');

        $this->assertEquals(Country::UNITED_STATES, $country);
    }

    public function test_throws_exception_for_invalid_country(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Only United States addresses are allowed');

        Country::fromString('BR');
    }

    public function test_can_validate_country_code(): void
    {
        $this->assertTrue(Country::isValid('US'));
        $this->assertTrue(Country::isValid('us'));
        $this->assertFalse(Country::isValid('BR'));
    }

    public function test_can_get_full_name(): void
    {
        $country = Country::UNITED_STATES;

        $this->assertEquals('United States', $country->getFullName());
    }
}
