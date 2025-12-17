<?php

namespace Tests\Unit\Domain\User\ValueObjects;

use App\Domain\User\ValueObjects\Email;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    public function test_can_create_valid_email(): void
    {
        $email = new Email('test@example.com');

        $this->assertEquals('test@example.com', $email->getValue());
    }

    public function test_can_convert_email_to_string(): void
    {
        $email = new Email('test@example.com');

        $this->assertEquals('test@example.com', (string) $email);
    }

    public function test_throws_exception_for_invalid_email(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid email address: invalid-email');

        new Email('invalid-email');
    }

    public function test_can_compare_equal_emails(): void
    {
        $email1 = new Email('test@example.com');
        $email2 = new Email('test@example.com');

        $this->assertTrue($email1->equals($email2));
    }

    public function test_can_compare_different_emails(): void
    {
        $email1 = new Email('test1@example.com');
        $email2 = new Email('test2@example.com');

        $this->assertFalse($email1->equals($email2));
    }

    public function test_can_convert_to_array(): void
    {
        $email = new Email('test@example.com');

        $this->assertEquals(['email' => 'test@example.com'], $email->toArray());
    }

    public function test_can_create_from_array(): void
    {
        $email = Email::fromArray(['email' => 'test@example.com']);

        $this->assertEquals('test@example.com', $email->getValue());
    }
}
