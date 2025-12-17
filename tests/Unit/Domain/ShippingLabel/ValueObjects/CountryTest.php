<?php

namespace Tests\Unit\Domain\ShippingLabel\ValueObjects;

use App\Domain\ShippingLabel\ValueObjects\Country;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CountryTest extends TestCase
{
    public function test_pode_criar_pais_valido(): void
    {
        $country = Country::UNITED_STATES;

        $this->assertEquals('US', $country->value);
    }

    public function test_pode_criar_de_string(): void
    {
        $country = Country::fromString('US');

        $this->assertEquals(Country::UNITED_STATES, $country);
    }

    public function test_pode_criar_de_string_lowercase(): void
    {
        $country = Country::fromString('us');

        $this->assertEquals(Country::UNITED_STATES, $country);
    }

    public function test_pode_criar_de_string_com_espacos(): void
    {
        $country = Country::fromString('  US  ');

        $this->assertEquals(Country::UNITED_STATES, $country);
    }

    public function test_lanca_excecao_para_pais_invalido(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Only United States addresses are allowed');

        Country::fromString('BR');
    }

    public function test_pode_validar_codigo_pais(): void
    {
        $this->assertTrue(Country::isValid('US'));
        $this->assertTrue(Country::isValid('us'));
        $this->assertFalse(Country::isValid('BR'));
    }

    public function test_pode_obter_nome_completo(): void
    {
        $country = Country::UNITED_STATES;

        $this->assertEquals('United States', $country->getFullName());
    }
}

