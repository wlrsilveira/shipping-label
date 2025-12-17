<?php

namespace Tests\Unit\Domain\User\ValueObjects;

use App\Domain\User\ValueObjects\Name;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class NameTest extends TestCase
{
    public function test_pode_criar_nome_valido(): void
    {
        $name = new Name('John Doe');

        $this->assertEquals('John Doe', $name->getValue());
    }

    public function test_pode_converter_nome_para_string(): void
    {
        $name = new Name('John Doe');

        $this->assertEquals('John Doe', (string) $name);
    }

    public function test_lanca_excecao_para_nome_vazio(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Name cannot be empty');

        new Name('');
    }

    public function test_lanca_excecao_para_nome_apenas_espacos(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Name cannot be empty');

        new Name('   ');
    }

    public function test_lanca_excecao_para_nome_muito_longo(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Name cannot exceed 255 characters');

        new Name(str_repeat('a', 256));
    }

    public function test_pode_comparar_nomes_iguais(): void
    {
        $name1 = new Name('John Doe');
        $name2 = new Name('John Doe');

        $this->assertTrue($name1->equals($name2));
    }

    public function test_pode_comparar_nomes_diferentes(): void
    {
        $name1 = new Name('John Doe');
        $name2 = new Name('Jane Doe');

        $this->assertFalse($name1->equals($name2));
    }

    public function test_pode_converter_para_array(): void
    {
        $name = new Name('John Doe');

        $this->assertEquals(['name' => 'John Doe'], $name->toArray());
    }

    public function test_pode_criar_de_array(): void
    {
        $name = Name::fromArray(['name' => 'John Doe']);

        $this->assertEquals('John Doe', $name->getValue());
    }
}

