<?php

namespace Tests\Unit\Domain\User\ValueObjects;

use App\Domain\User\ValueObjects\Email;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    public function test_pode_criar_email_valido(): void
    {
        $email = new Email('test@example.com');

        $this->assertEquals('test@example.com', $email->getValue());
    }

    public function test_pode_converter_email_para_string(): void
    {
        $email = new Email('test@example.com');

        $this->assertEquals('test@example.com', (string) $email);
    }

    public function test_lanca_excecao_para_email_invalido(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid email address: invalid-email');

        new Email('invalid-email');
    }

    public function test_pode_comparar_emails_iguais(): void
    {
        $email1 = new Email('test@example.com');
        $email2 = new Email('test@example.com');

        $this->assertTrue($email1->equals($email2));
    }

    public function test_pode_comparar_emails_diferentes(): void
    {
        $email1 = new Email('test1@example.com');
        $email2 = new Email('test2@example.com');

        $this->assertFalse($email1->equals($email2));
    }

    public function test_pode_converter_para_array(): void
    {
        $email = new Email('test@example.com');

        $this->assertEquals(['email' => 'test@example.com'], $email->toArray());
    }

    public function test_pode_criar_de_array(): void
    {
        $email = Email::fromArray(['email' => 'test@example.com']);

        $this->assertEquals('test@example.com', $email->getValue());
    }
}

