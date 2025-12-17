<?php

namespace Tests\Unit\Domain\ShippingLabel\ValueObjects;

use App\Domain\ShippingLabel\ValueObjects\USState;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class USStateTest extends TestCase
{
    public function test_pode_criar_estado_valido(): void
    {
        $state = USState::CALIFORNIA;

        $this->assertEquals('CA', $state->value);
    }

    public function test_pode_criar_de_string(): void
    {
        $state = USState::fromString('CA');

        $this->assertEquals(USState::CALIFORNIA, $state);
    }

    public function test_pode_criar_de_string_lowercase(): void
    {
        $state = USState::fromString('ca');

        $this->assertEquals(USState::CALIFORNIA, $state);
    }

    public function test_pode_criar_de_string_com_espacos(): void
    {
        $state = USState::fromString('  CA  ');

        $this->assertEquals(USState::CALIFORNIA, $state);
    }

    public function test_lanca_excecao_para_estado_invalido(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid US state code: XX');

        USState::fromString('XX');
    }

    public function test_pode_validar_codigo_estado(): void
    {
        $this->assertTrue(USState::isValid('CA'));
        $this->assertTrue(USState::isValid('ca'));
        $this->assertFalse(USState::isValid('XX'));
    }

    public function test_pode_obter_nome_completo_dos_estados(): void
    {
        $this->assertEquals('California', USState::CALIFORNIA->getFullName());
        $this->assertEquals('New York', USState::NEW_YORK->getFullName());
        $this->assertEquals('Texas', USState::TEXAS->getFullName());
        $this->assertEquals('District of Columbia', USState::DISTRICT_OF_COLUMBIA->getFullName());
    }

    public function test_possui_todos_estados_e_territorios(): void
    {
        $this->assertNotNull(USState::ALABAMA);
        $this->assertNotNull(USState::ALASKA);
        $this->assertNotNull(USState::DISTRICT_OF_COLUMBIA);
        $this->assertNotNull(USState::PUERTO_RICO);
        $this->assertNotNull(USState::GUAM);
        $this->assertNotNull(USState::VIRGIN_ISLANDS);
    }
}

