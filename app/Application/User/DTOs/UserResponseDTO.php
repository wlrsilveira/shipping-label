<?php

namespace App\Application\User\DTOs;

use App\Domain\User\Entities\User;

final readonly class UserResponseDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public ?string $emailVerifiedAt,
        public string $createdAt,
        public string $updatedAt,
    ) {
    }

    public static function fromDomain(User $user): self
    {
        return new self(
            id: $user->getId() ?? 0,
            name: $user->getName()->getValue(),
            email: $user->getEmail()->getValue(),
            emailVerifiedAt: $user->getEmailVerifiedAt()?->format('Y-m-d H:i:s'),
            createdAt: $user->getCreatedAt()?->format('Y-m-d H:i:s') ?? now()->format('Y-m-d H:i:s'),
            updatedAt: $user->getUpdatedAt()?->format('Y-m-d H:i:s') ?? now()->format('Y-m-d H:i:s'),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $this->emailVerifiedAt,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
