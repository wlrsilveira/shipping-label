<?php

namespace Tests\Unit\Domain\ShippingLabel\ValueObjects;

use App\Domain\ShippingLabel\ValueObjects\Address;
use App\Domain\ShippingLabel\ValueObjects\Country;
use App\Domain\ShippingLabel\ValueObjects\USState;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class AddressTest extends TestCase
{
    public function test_pode_criar_endereco_valido(): void
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

    public function test_pode_criar_endereco_sem_campos_opcionais(): void
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

    public function test_lanca_excecao_para_rua_vazia(): void
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

    public function test_lanca_excecao_para_rua_muito_longa(): void
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

    public function test_lanca_excecao_para_cidade_vazia(): void
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

    public function test_lanca_excecao_para_cidade_muito_longa(): void
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

    public function test_lanca_excecao_para_cep_invalido(): void
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

    public function test_aceita_cep_formato_5_digitos(): void
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

    public function test_aceita_cep_formato_9_digitos(): void
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

    public function test_lanca_excecao_para_telefone_invalido(): void
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

    public function test_aceita_telefone_valido(): void
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

    public function test_pode_converter_para_array(): void
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

    public function test_pode_criar_de_array(): void
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

    public function test_pode_comparar_enderecos_iguais(): void
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

    public function test_pode_comparar_enderecos_diferentes(): void
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

