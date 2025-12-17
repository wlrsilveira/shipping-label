<?php

namespace Tests\Unit\Application\Auth\DTOs;

use App\Application\Auth\DTOs\RegisterDTO;
use App\Application\User\DTOs\CreateUserDTO;
use App\Domain\User\ValueObjects\Email;
use App\Domain\User\ValueObjects\Name;
use PHPUnit\Framework\TestCase;

class RegisterDTOTest extends TestCase
{
    public function test_can_create_dto(): void
    {
        $createUserDTO = new CreateUserDTO(
            name: new Name('Test User'),
            email: new Email('test@example.com'),
            plainPassword: 'password'
        );

        $dto = new RegisterDTO($createUserDTO);

        $this->assertEquals($createUserDTO, $dto->userData);
    }

    public function test_can_create_from_array(): void
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ];

        $dto = RegisterDTO::fromArray($data);

        $this->assertEquals('Test User', $dto->userData->name->getValue());
        $this->assertEquals('test@example.com', $dto->userData->email->getValue());
        $this->assertEquals('password', $dto->userData->plainPassword);
    }
}
