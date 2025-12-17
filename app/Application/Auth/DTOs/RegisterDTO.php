<?php

namespace App\Application\Auth\DTOs;

use App\Application\User\DTOs\CreateUserDTO;

final readonly class RegisterDTO
{
    public function __construct(
        public CreateUserDTO $userData
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            userData: CreateUserDTO::fromArray($data)
        );
    }
}

