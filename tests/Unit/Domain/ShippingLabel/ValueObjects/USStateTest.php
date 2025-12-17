<?php

namespace Tests\Unit\Domain\ShippingLabel\ValueObjects;

use App\Domain\ShippingLabel\ValueObjects\USState;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class USStateTest extends TestCase
{
    public function test_can_create_valid_state(): void
    {
        $state = USState::CALIFORNIA;

        $this->assertEquals('CA', $state->value);
    }

    public function test_can_create_from_string(): void
    {
        $state = USState::fromString('CA');

        $this->assertEquals(USState::CALIFORNIA, $state);
    }

    public function test_can_create_from_lowercase_string(): void
    {
        $state = USState::fromString('ca');

        $this->assertEquals(USState::CALIFORNIA, $state);
    }

    public function test_can_create_from_string_with_spaces(): void
    {
        $state = USState::fromString('  CA  ');

        $this->assertEquals(USState::CALIFORNIA, $state);
    }

    public function test_throws_exception_for_invalid_state(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid US state code: XX');

        USState::fromString('XX');
    }

    public function test_can_validate_state_code(): void
    {
        $this->assertTrue(USState::isValid('CA'));
        $this->assertTrue(USState::isValid('ca'));
        $this->assertFalse(USState::isValid('XX'));
    }

    public function test_can_get_full_name_of_states(): void
    {
        $this->assertEquals('California', USState::CALIFORNIA->getFullName());
        $this->assertEquals('New York', USState::NEW_YORK->getFullName());
        $this->assertEquals('Texas', USState::TEXAS->getFullName());
        $this->assertEquals('District of Columbia', USState::DISTRICT_OF_COLUMBIA->getFullName());
    }

    public function test_has_all_states_and_territories(): void
    {
        $this->assertNotNull(USState::ALABAMA);
        $this->assertNotNull(USState::ALASKA);
        $this->assertNotNull(USState::DISTRICT_OF_COLUMBIA);
        $this->assertNotNull(USState::PUERTO_RICO);
        $this->assertNotNull(USState::GUAM);
        $this->assertNotNull(USState::VIRGIN_ISLANDS);
    }
}
