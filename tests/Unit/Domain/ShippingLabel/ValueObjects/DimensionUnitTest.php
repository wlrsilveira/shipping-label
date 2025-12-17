<?php

namespace Tests\Unit\Domain\ShippingLabel\ValueObjects;

use App\Domain\ShippingLabel\ValueObjects\DimensionUnit;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class DimensionUnitTest extends TestCase
{
    public function test_pode_criar_unidade_de_dimensao_valida(): void
    {
        $unit = DimensionUnit::INCH;

        $this->assertEquals('in', $unit->value);
    }

    public function test_pode_criar_de_string(): void
    {
        $unit = DimensionUnit::fromString('in');

        $this->assertEquals(DimensionUnit::INCH, $unit);
    }

    public function test_pode_criar_de_string_uppercase(): void
    {
        $unit = DimensionUnit::fromString('IN');

        $this->assertEquals(DimensionUnit::INCH, $unit);
    }

    public function test_pode_criar_de_string_com_espacos(): void
    {
        $unit = DimensionUnit::fromString('  cm  ');

        $this->assertEquals(DimensionUnit::CENTIMETER, $unit);
    }

    public function test_lanca_excecao_para_unidade_invalida(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid dimension unit: invalid');

        DimensionUnit::fromString('invalid');
    }

    public function test_pode_validar_unidade(): void
    {
        $this->assertTrue(DimensionUnit::isValid('in'));
        $this->assertTrue(DimensionUnit::isValid('cm'));
        $this->assertFalse(DimensionUnit::isValid('invalid'));
    }

    public function test_pode_obter_nome_completo(): void
    {
        $this->assertEquals('Inch', DimensionUnit::INCH->getFullName());
        $this->assertEquals('Centimeter', DimensionUnit::CENTIMETER->getFullName());
        $this->assertEquals('Millimeter', DimensionUnit::MILLIMETER->getFullName());
    }

    public function test_pode_verificar_se_e_metrica(): void
    {
        $this->assertTrue(DimensionUnit::CENTIMETER->isMetric());
        $this->assertTrue(DimensionUnit::MILLIMETER->isMetric());
        $this->assertFalse(DimensionUnit::INCH->isMetric());
    }

    public function test_pode_verificar_se_e_imperial(): void
    {
        $this->assertTrue(DimensionUnit::INCH->isImperial());
        $this->assertFalse(DimensionUnit::CENTIMETER->isImperial());
        $this->assertFalse(DimensionUnit::MILLIMETER->isImperial());
    }
}

