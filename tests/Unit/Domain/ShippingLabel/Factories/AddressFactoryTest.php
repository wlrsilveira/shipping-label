<?php

namespace Tests\Unit\Domain\ShippingLabel\Factories;

use App\Domain\ShippingLabel\Factories\AddressFactory;
use App\Domain\ShippingLabel\ValueObjects\Address;
use App\Domain\ShippingLabel\ValueObjects\Country;
use App\Domain\ShippingLabel\ValueObjects\USState;
use PHPUnit\Framework\TestCase;

class AddressFactoryTest extends TestCase
{
    public function test_pode_criar_endereco(): void
    {
        $address = AddressFactory::create(
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

        $this->assertInstanceOf(Address::class, $address);
        $this->assertEquals('123 Main St', $address->getStreet1());
        $this->assertEquals('Apt 4', $address->getStreet2());
        $this->assertEquals('Los Angeles', $address->getCity());
        $this->assertEquals(USState::CALIFORNIA, $address->getState());
    }

    public function test_pode_criar_endereco_padrao(): void
    {
        $address = AddressFactory::makeDefault();

        $this->assertInstanceOf(Address::class, $address);
        $this->assertEquals('417 Montgomery Street', $address->getStreet1());
        $this->assertEquals('Floor 5', $address->getStreet2());
        $this->assertEquals('San Francisco', $address->getCity());
        $this->assertEquals(USState::CALIFORNIA, $address->getState());
        $this->assertEquals('94104', $address->getZipCode());
        $this->assertEquals('John Doe', $address->getName());
    }

    public function test_pode_criar_endereco_sem_campos_opcionais(): void
    {
        $address = AddressFactory::makeWithoutOptionalFields();

        $this->assertInstanceOf(Address::class, $address);
        $this->assertEquals('123 Main Street', $address->getStreet1());
        $this->assertNull($address->getStreet2());
        $this->assertEquals('New York', $address->getCity());
        $this->assertEquals(USState::NEW_YORK, $address->getState());
        $this->assertNull($address->getName());
        $this->assertNull($address->getPhone());
        $this->assertNull($address->getCompany());
    }

    public function test_pode_criar_endereco_residencial(): void
    {
        $address = AddressFactory::makeResidential();

        $this->assertInstanceOf(Address::class, $address);
        $this->assertEquals('123 Residential Drive', $address->getStreet1());
        $this->assertEquals('Apt 4B', $address->getStreet2());
        $this->assertEquals('Los Angeles', $address->getCity());
        $this->assertEquals(USState::CALIFORNIA, $address->getState());
        $this->assertEquals('Jane Smith', $address->getName());
        $this->assertNull($address->getCompany());
    }

    public function test_pode_criar_endereco_residencial_com_estado_customizado(): void
    {
        $address = AddressFactory::makeResidential(state: USState::TEXAS);

        $this->assertEquals(USState::TEXAS, $address->getState());
    }

    public function test_pode_criar_endereco_comercial(): void
    {
        $address = AddressFactory::makeCommercial();

        $this->assertInstanceOf(Address::class, $address);
        $this->assertEquals('456 Business Avenue', $address->getStreet1());
        $this->assertEquals('Suite 100', $address->getStreet2());
        $this->assertEquals('New York', $address->getCity());
        $this->assertEquals(USState::NEW_YORK, $address->getState());
        $this->assertEquals('Acme Corporation', $address->getCompany());
    }

    public function test_pode_criar_endereco_comercial_com_empresa_customizada(): void
    {
        $address = AddressFactory::makeCommercial(company: 'Custom Corp');

        $this->assertEquals('Custom Corp', $address->getCompany());
    }

    public function test_pode_criar_de_array(): void
    {
        $data = [
            'street1' => '789 Test St',
            'street2' => null,
            'city' => 'Austin',
            'state' => 'TX',
            'zip' => '73301',
            'country' => 'US',
        ];

        $address = AddressFactory::fromArray($data);

        $this->assertInstanceOf(Address::class, $address);
        $this->assertEquals('789 Test St', $address->getStreet1());
        $this->assertEquals('Austin', $address->getCity());
        $this->assertEquals(USState::TEXAS, $address->getState());
    }
}

