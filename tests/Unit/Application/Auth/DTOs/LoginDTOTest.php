<?php

namespace Tests\Unit\Application\Auth\DTOs;

use App\Application\Auth\DTOs\LoginDTO;
use App\Domain\User\ValueObjects\Email;
use PHPUnit\Framework\TestCase;

class LoginDTOTest extends TestCase
{
    public function test_can_create_dto(): void
    {
        $email = new Email('test@example.com');
        $dto = new LoginDTO($email, 'password', true);

        $this->assertEquals($email, $dto->email);
        $this->assertEquals('password', $dto->password);
        $this->assertTrue($dto->remember);
    }

    public function test_default_remember_is_false(): void
    {
        $email = new Email('test@example.com');
        $dto = new LoginDTO($email, 'password');

        $this->assertFalse($dto->remember);
    }

    public function test_can_create_from_array(): void
    {
        $data = [
            'email' => 'test@example.com',
            'password' => 'password',
            'remember' => true,
        ];

        $dto = LoginDTO::fromArray($data);

        $this->assertEquals('test@example.com', $dto->email->getValue());
        $this->assertEquals('password', $dto->password);
        $this->assertTrue($dto->remember);
    }

    public function test_can_create_from_array_without_remember(): void
    {
        $data = [
            'email' => 'test@example.com',
            'password' => 'password',
        ];

        $dto = LoginDTO::fromArray($data);

        $this->assertFalse($dto->remember);
    }
}
