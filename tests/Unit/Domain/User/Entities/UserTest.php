<?php

namespace Tests\Unit\Domain\User\Entities;

use App\Domain\User\Entities\User;
use App\Domain\User\ValueObjects\Email;
use App\Domain\User\ValueObjects\Name;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private function createUser(): User
    {
        return new User(
            id: 1,
            name: new Name('John Doe'),
            email: new Email('john@example.com'),
            hashedPassword: 'hashed_password'
        );
    }

    public function test_pode_criar_usuario(): void
    {
        $user = $this->createUser();

        $this->assertEquals(1, $user->getId());
        $this->assertEquals('John Doe', $user->getName()->getValue());
        $this->assertEquals('john@example.com', $user->getEmail()->getValue());
        $this->assertEquals('hashed_password', $user->getHashedPassword());
    }

    public function test_pode_atualizar_nome(): void
    {
        $user = $this->createUser();
        $newName = new Name('Jane Doe');

        $user->updateName($newName);

        $this->assertEquals('Jane Doe', $user->getName()->getValue());
    }

    public function test_pode_atualizar_email(): void
    {
        $user = $this->createUser();
        $newEmail = new Email('jane@example.com');

        $user->updateEmail($newEmail);

        $this->assertEquals('jane@example.com', $user->getEmail()->getValue());
    }

    public function test_pode_atualizar_senha(): void
    {
        $user = $this->createUser();

        $user->updatePassword('new_hashed_password');

        $this->assertEquals('new_hashed_password', $user->getHashedPassword());
    }

    public function test_pode_criar_usuario_sem_id(): void
    {
        $user = new User(
            id: null,
            name: new Name('John Doe'),
            email: new Email('john@example.com'),
            hashedPassword: 'hashed_password'
        );

        $this->assertNull($user->getId());
    }

    public function test_pode_obter_datas(): void
    {
        $createdAt = new \DateTimeImmutable('2025-01-01 10:00:00');
        $updatedAt = new \DateTimeImmutable('2025-01-02 10:00:00');

        $user = new User(
            id: 1,
            name: new Name('John Doe'),
            email: new Email('john@example.com'),
            hashedPassword: 'hashed_password',
            emailVerifiedAt: $createdAt,
            createdAt: $createdAt,
            updatedAt: $updatedAt
        );

        $this->assertEquals($createdAt, $user->getEmailVerifiedAt());
        $this->assertEquals($createdAt, $user->getCreatedAt());
        $this->assertEquals($updatedAt, $user->getUpdatedAt());
    }
}

