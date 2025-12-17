<?php

namespace App\Application\User\DTOs;

use App\Domain\User\ValueObjects\Email;
use App\Domain\User\ValueObjects\Name;

final readonly class CreateUserDTO
{
    public function __construct(
        public Name $name,
        public Email $email,
        public string $plainPassword,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: new Name($data['name']),
            email: new Email($data['email']),
            plainPassword: $data['password'],
        );
    }
}

