<?php

namespace Tests\Unit\Domain\ShippingLabel\ValueObjects;

use App\Domain\ShippingLabel\ValueObjects\WeightUnit;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class WeightUnitTest extends TestCase
{
    public function test_pode_criar_unidade_de_peso_valida(): void
    {
        $unit = WeightUnit::POUND;

        $this->assertEquals('lb', $unit->value);
    }

    public function test_pode_criar_de_string(): void
    {
        $unit = WeightUnit::fromString('lb');

        $this->assertEquals(WeightUnit::POUND, $unit);
    }

    public function test_pode_criar_de_string_uppercase(): void
    {
        $unit = WeightUnit::fromString('LB');

        $this->assertEquals(WeightUnit::POUND, $unit);
    }

    public function test_pode_criar_de_string_com_espacos(): void
    {
        $unit = WeightUnit::fromString('  kg  ');

        $this->assertEquals(WeightUnit::KILOGRAM, $unit);
    }

    public function test_lanca_excecao_para_unidade_invalida(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid weight unit: invalid');

        WeightUnit::fromString('invalid');
    }

    public function test_pode_validar_unidade(): void
    {
        $this->assertTrue(WeightUnit::isValid('lb'));
        $this->assertTrue(WeightUnit::isValid('kg'));
        $this->assertFalse(WeightUnit::isValid('invalid'));
    }

    public function test_pode_obter_nome_completo(): void
    {
        $this->assertEquals('Pound', WeightUnit::POUND->getFullName());
        $this->assertEquals('Ounce', WeightUnit::OUNCE->getFullName());
        $this->assertEquals('Kilogram', WeightUnit::KILOGRAM->getFullName());
        $this->assertEquals('Gram', WeightUnit::GRAM->getFullName());
    }

    public function test_pode_verificar_se_e_metrica(): void
    {
        $this->assertTrue(WeightUnit::KILOGRAM->isMetric());
        $this->assertTrue(WeightUnit::GRAM->isMetric());
        $this->assertFalse(WeightUnit::POUND->isMetric());
        $this->assertFalse(WeightUnit::OUNCE->isMetric());
    }

    public function test_pode_verificar_se_e_imperial(): void
    {
        $this->assertTrue(WeightUnit::POUND->isImperial());
        $this->assertTrue(WeightUnit::OUNCE->isImperial());
        $this->assertFalse(WeightUnit::KILOGRAM->isImperial());
        $this->assertFalse(WeightUnit::GRAM->isImperial());
    }
}

