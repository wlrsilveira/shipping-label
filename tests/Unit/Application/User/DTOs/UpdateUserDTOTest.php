<?php

namespace Tests\Unit\Application\User\DTOs;

use App\Application\User\DTOs\UpdateUserDTO;
use App\Domain\User\ValueObjects\Email;
use App\Domain\User\ValueObjects\Name;
use PHPUnit\Framework\TestCase;

class UpdateUserDTOTest extends TestCase
{
    public function test_pode_criar_dto_com_senha(): void
    {
        $name = new Name('Test User');
        $email = new Email('test@example.com');

        $dto = new UpdateUserDTO($name, $email, 'password');

        $this->assertEquals($name, $dto->name);
        $this->assertEquals($email, $dto->email);
        $this->assertEquals('password', $dto->plainPassword);
    }

    public function test_pode_criar_dto_sem_senha(): void
    {
        $name = new Name('Test User');
        $email = new Email('test@example.com');

        $dto = new UpdateUserDTO($name, $email);

        $this->assertEquals($name, $dto->name);
        $this->assertEquals($email, $dto->email);
        $this->assertNull($dto->plainPassword);
    }

    public function test_pode_criar_de_array_com_senha(): void
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'newpassword',
        ];

        $dto = UpdateUserDTO::fromArray($data);

        $this->assertEquals('Test User', $dto->name->getValue());
        $this->assertEquals('test@example.com', $dto->email->getValue());
        $this->assertEquals('newpassword', $dto->plainPassword);
    }

    public function test_pode_criar_de_array_sem_senha(): void
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ];

        $dto = UpdateUserDTO::fromArray($data);

        $this->assertEquals('Test User', $dto->name->getValue());
        $this->assertEquals('test@example.com', $dto->email->getValue());
        $this->assertNull($dto->plainPassword);
    }
}

