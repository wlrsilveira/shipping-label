<?php

namespace App\Infrastructure\User\Mappers;

use App\Domain\User\Entities\User;
use App\Domain\User\ValueObjects\Email;
use App\Domain\User\ValueObjects\Name;
use App\Models\User as EloquentUser;

class UserMapper
{
    public function toDomain(EloquentUser $eloquentUser): User
    {
        return new User(
            id: $eloquentUser->id,
            name: new Name($eloquentUser->name),
            email: new Email($eloquentUser->email),
            hashedPassword: $eloquentUser->password,
            emailVerifiedAt: $eloquentUser->email_verified_at
                ? \DateTimeImmutable::createFromInterface($eloquentUser->email_verified_at)
                : null,
            createdAt: $eloquentUser->created_at
                ? \DateTimeImmutable::createFromInterface($eloquentUser->created_at)
                : null,
            updatedAt: $eloquentUser->updated_at
                ? \DateTimeImmutable::createFromInterface($eloquentUser->updated_at)
                : null,
        );
    }

    public function toEloquent(User $domainUser): EloquentUser
    {
        $eloquentUser = EloquentUser::find($domainUser->getId()) ?? new EloquentUser();

        $eloquentUser->name = $domainUser->getName()->getValue();
        $eloquentUser->email = $domainUser->getEmail()->getValue();
        $eloquentUser->password = $domainUser->getHashedPassword();

        if ($domainUser->getEmailVerifiedAt()) {
            $eloquentUser->email_verified_at = $domainUser->getEmailVerifiedAt();
        }

        return $eloquentUser;
    }
}

