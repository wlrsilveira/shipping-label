<?php

namespace Tests\Unit\Domain\ShippingLabel\ValueObjects;

use App\Domain\ShippingLabel\ValueObjects\Address;
use App\Domain\ShippingLabel\ValueObjects\Country;
use App\Domain\ShippingLabel\ValueObjects\USState;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class AddressTest extends TestCase
{
    public function test_can_create_valid_address(): void
    {
        $address = new Address(
            street1: '123 Main St',
            street2: 'Apt 4',
            city: 'Los Angeles',
            state: USState::CALIFORNIA,
            zipCode: '90001',
            country: Country::UNITED_STATES,
            name: 'John Doe',
            phone: '1234567890',
            company: 'Acme Corp'
        );

        $this->assertEquals('123 Main St', $address->getStreet1());
        $this->assertEquals('Apt 4', $address->getStreet2());
        $this->assertEquals('Los Angeles', $address->getCity());
        $this->assertEquals(USState::CALIFORNIA, $address->getState());
        $this->assertEquals('90001', $address->getZipCode());
        $this->assertEquals(Country::UNITED_STATES, $address->getCountry());
        $this->assertEquals('John Doe', $address->getName());
        $this->assertEquals('1234567890', $address->getPhone());
        $this->assertEquals('Acme Corp', $address->getCompany());
    }

    public function test_can_create_address_without_optional_fields(): void
    {
        $address = new Address(
            street1: '123 Main St',
            street2: null,
            city: 'Los Angeles',
            state: USState::CALIFORNIA,
            zipCode: '90001',
            country: Country::UNITED_STATES
        );

        $this->assertNull($address->getStreet2());
        $this->assertNull($address->getName());
        $this->assertNull($address->getPhone());
        $this->assertNull($address->getCompany());
    }

    public function test_throws_exception_for_empty_street(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Street address cannot be empty');

        new Address(
            street1: '',
            street2: null,
            city: 'Los Angeles',
            state: USState::CALIFORNIA,
            zipCode: '90001',
            country: Country::UNITED_STATES
        );
    }

    public function test_throws_exception_for_street_too_long(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Street address cannot exceed 255 characters');

        new Address(
            street1: str_repeat('a', 256),
            street2: null,
            city: 'Los Angeles',
            state: USState::CALIFORNIA,
            zipCode: '90001',
            country: Country::UNITED_STATES
        );
    }

    public function test_throws_exception_for_empty_city(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('City cannot be empty');

        new Address(
            street1: '123 Main St',
            street2: null,
            city: '',
            state: USState::CALIFORNIA,
            zipCode: '90001',
            country: Country::UNITED_STATES
        );
    }

    public function test_throws_exception_for_city_too_long(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('City cannot exceed 100 characters');

        new Address(
            street1: '123 Main St',
            street2: null,
            city: str_repeat('a', 101),
            state: USState::CALIFORNIA,
            zipCode: '90001',
            country: Country::UNITED_STATES
        );
    }

    public function test_throws_exception_for_invalid_zip_code(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid US ZIP code format');

        new Address(
            street1: '123 Main St',
            street2: null,
            city: 'Los Angeles',
            state: USState::CALIFORNIA,
            zipCode: 'invalid',
            country: Country::UNITED_STATES
        );
    }

    public function test_accepts_5_digit_zip_code_format(): void
    {
        $address = new Address(
            street1: '123 Main St',
            street2: null,
            city: 'Los Angeles',
            state: USState::CALIFORNIA,
            zipCode: '90001',
            country: Country::UNITED_STATES
        );

        $this->assertEquals('90001', $address->getZipCode());
    }

    public function test_accepts_9_digit_zip_code_format(): void
    {
        $address = new Address(
            street1: '123 Main St',
            street2: null,
            city: 'Los Angeles',
            state: USState::CALIFORNIA,
            zipCode: '90001-1234',
            country: Country::UNITED_STATES
        );

        $this->assertEquals('90001-1234', $address->getZipCode());
    }

    public function test_throws_exception_for_invalid_phone(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid phone number format');

        new Address(
            street1: '123 Main St',
            street2: null,
            city: 'Los Angeles',
            state: USState::CALIFORNIA,
            zipCode: '90001',
            country: Country::UNITED_STATES,
            phone: '123'
        );
    }

    public function test_accepts_valid_phone(): void
    {
        $address = new Address(
            street1: '123 Main St',
            street2: null,
            city: 'Los Angeles',
            state: USState::CALIFORNIA,
            zipCode: '90001',
            country: Country::UNITED_STATES,
            phone: '(123) 456-7890'
        );

        $this->assertEquals('(123) 456-7890', $address->getPhone());
    }

    public function test_can_convert_to_array(): void
    {
        $address = new Address(
            street1: '123 Main St',
            street2: 'Apt 4',
            city: 'Los Angeles',
            state: USState::CALIFORNIA,
            zipCode: '90001',
            country: Country::UNITED_STATES,
            name: 'John Doe',
            phone: '1234567890',
            company: 'Acme Corp'
        );

        $expected = [
            'street1' => '123 Main St',
            'street2' => 'Apt 4',
            'city' => 'Los Angeles',
            'state' => 'CA',
            'zip' => '90001',
            'country' => 'US',
            'name' => 'John Doe',
            'phone' => '1234567890',
            'company' => 'Acme Corp',
        ];

        $this->assertEquals($expected, $address->toArray());
    }

    public function test_can_create_from_array(): void
    {
        $data = [
            'street1' => '123 Main St',
            'street2' => 'Apt 4',
            'city' => 'Los Angeles',
            'state' => 'CA',
            'zip' => '90001',
            'country' => 'US',
            'name' => 'John Doe',
            'phone' => '1234567890',
            'company' => 'Acme Corp',
        ];

        $address = Address::fromArray($data);

        $this->assertEquals('123 Main St', $address->getStreet1());
        $this->assertEquals('Apt 4', $address->getStreet2());
        $this->assertEquals('Los Angeles', $address->getCity());
        $this->assertEquals(USState::CALIFORNIA, $address->getState());
    }

    public function test_can_compare_equal_addresses(): void
    {
        $address1 = new Address(
            street1: '123 Main St',
            street2: null,
            city: 'Los Angeles',
            state: USState::CALIFORNIA,
            zipCode: '90001',
            country: Country::UNITED_STATES
        );

        $address2 = new Address(
            street1: '123 Main St',
            street2: null,
            city: 'Los Angeles',
            state: USState::CALIFORNIA,
            zipCode: '90001',
            country: Country::UNITED_STATES
        );

        $this->assertTrue($address1->equals($address2));
    }

    public function test_can_compare_different_addresses(): void
    {
        $address1 = new Address(
            street1: '123 Main St',
            street2: null,
            city: 'Los Angeles',
            state: USState::CALIFORNIA,
            zipCode: '90001',
            country: Country::UNITED_STATES
        );

        $address2 = new Address(
            street1: '456 Oak Ave',
            street2: null,
            city: 'Los Angeles',
            state: USState::CALIFORNIA,
            zipCode: '90001',
            country: Country::UNITED_STATES
        );

        $this->assertFalse($address1->equals($address2));
    }
}
