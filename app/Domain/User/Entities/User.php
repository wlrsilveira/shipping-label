<?php

namespace App\Domain\User\Entities;

use App\Domain\User\ValueObjects\Email;
use App\Domain\User\ValueObjects\Name;

class User
{
    public function __construct(
        private ?int $id,
        private Name $name,
        private Email $email,
        private string $hashedPassword,
        private ?\DateTimeImmutable $emailVerifiedAt = null,
        private ?\DateTimeImmutable $createdAt = null,
        private ?\DateTimeImmutable $updatedAt = null,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getHashedPassword(): string
    {
        return $this->hashedPassword;
    }

    public function getEmailVerifiedAt(): ?\DateTimeImmutable
    {
        return $this->emailVerifiedAt;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function updateName(Name $name): void
    {
        $this->name = $name;
    }

    public function updateEmail(Email $email): void
    {
        $this->email = $email;
    }

    public function updatePassword(string $hashedPassword): void
    {
        $this->hashedPassword = $hashedPassword;
    }
}

