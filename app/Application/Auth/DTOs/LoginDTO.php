<?php

namespace App\Application\Auth\DTOs;

use App\Domain\User\ValueObjects\Email;

final readonly class LoginDTO
{
    public function __construct(
        public Email $email,
        public string $password,
        public bool $remember = false,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            email: new Email($data['email']),
            password: $data['password'],
            remember: $data['remember'] ?? false,
        );
    }
}

