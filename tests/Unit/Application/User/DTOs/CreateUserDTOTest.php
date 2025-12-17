<?php

namespace Tests\Unit\Application\User\DTOs;

use App\Application\User\DTOs\CreateUserDTO;
use App\Domain\User\ValueObjects\Email;
use App\Domain\User\ValueObjects\Name;
use PHPUnit\Framework\TestCase;

class CreateUserDTOTest extends TestCase
{
    public function test_can_create_dto(): void
    {
        $name = new Name('Test User');
        $email = new Email('test@example.com');

        $dto = new CreateUserDTO($name, $email, 'password');

        $this->assertEquals($name, $dto->name);
        $this->assertEquals($email, $dto->email);
        $this->assertEquals('password', $dto->plainPassword);
    }

    public function test_can_create_from_array(): void
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        $dto = CreateUserDTO::fromArray($data);

        $this->assertEquals('Test User', $dto->name->getValue());
        $this->assertEquals('test@example.com', $dto->email->getValue());
        $this->assertEquals('password123', $dto->plainPassword);
    }
}
