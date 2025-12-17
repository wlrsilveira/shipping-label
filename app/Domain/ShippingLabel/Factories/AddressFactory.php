<?php

namespace App\Domain\ShippingLabel\Factories;

use App\Domain\ShippingLabel\ValueObjects\Address;
use App\Domain\ShippingLabel\ValueObjects\USState;
use App\Domain\ShippingLabel\ValueObjects\Country;

class AddressFactory
{
    public static function create(
        string $street1,
        ?string $street2,
        string $city,
        USState $state,
        string $zipCode,
        Country $country = Country::UNITED_STATES,
        ?string $name = null,
        ?string $phone = null,
        ?string $company = null,
    ): Address {
        return new Address(
            street1: $street1,
            street2: $street2,
            city: $city,
            state: $state,
            zipCode: $zipCode,
            country: $country,
            name: $name,
            phone: $phone,
            company: $company,
        );
    }

    public static function makeDefault(): Address
    {
        return self::create(
            street1: '417 Montgomery Street',
            street2: 'Floor 5',
            city: 'San Francisco',
            state: USState::CALIFORNIA,
            zipCode: '94104',
            country: Country::UNITED_STATES,
            name: 'John Doe',
            phone: '4155551234',
            company: null,
        );
    }

    public static function makeWithoutOptionalFields(): Address
    {
        return self::create(
            street1: '123 Main Street',
            street2: null,
            city: 'New York',
            state: USState::NEW_YORK,
            zipCode: '10001',
            country: Country::UNITED_STATES,
            name: null,
            phone: null,
            company: null,
        );
    }

    public static function makeResidential(
        ?string $name = 'Jane Smith',
        ?USState $state = null
    ): Address {
        return self::create(
            street1: '123 Residential Drive',
            street2: 'Apt 4B',
            city: 'Los Angeles',
            state: $state ?? USState::CALIFORNIA,
            zipCode: '90001',
            country: Country::UNITED_STATES,
            name: $name,
            phone: '3105551234',
            company: null,
        );
    }

    public static function makeCommercial(
        ?string $company = 'Acme Corporation',
        ?USState $state = null
    ): Address {
        return self::create(
            street1: '456 Business Avenue',
            street2: 'Suite 100',
            city: 'New York',
            state: $state ?? USState::NEW_YORK,
            zipCode: '10001',
            country: Country::UNITED_STATES,
            name: 'Business Manager',
            phone: '2125551234',
            company: $company,
        );
    }

    public static function fromArray(array $data): Address
    {
        return Address::fromArray($data);
    }
}

