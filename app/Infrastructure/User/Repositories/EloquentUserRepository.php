<?php

namespace App\Infrastructure\User\Repositories;

use App\Domain\User\Entities\User;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\ValueObjects\Email;
use App\Infrastructure\User\Mappers\UserMapper;
use App\Models\User as EloquentUser;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function __construct(
        private UserMapper $mapper
    ) {
    }

    public function findById(int $id): ?User
    {
        $eloquentUser = EloquentUser::find($id);

        return $eloquentUser ? $this->mapper->toDomain($eloquentUser) : null;
    }

    public function findByEmail(Email $email): ?User
    {
        $eloquentUser = EloquentUser::where('email', $email->getValue())->first();

        return $eloquentUser ? $this->mapper->toDomain($eloquentUser) : null;
    }

    public function save(User $user): User
    {
        if ($user->getId() === null) {
            $eloquentUser = new EloquentUser();
        } else {
            $eloquentUser = EloquentUser::findOrFail($user->getId());
        }

        $eloquentUser->name = $user->getName()->getValue();
        $eloquentUser->email = $user->getEmail()->getValue();
        $eloquentUser->password = $user->getHashedPassword();

        if ($user->getEmailVerifiedAt()) {
            $eloquentUser->email_verified_at = $user->getEmailVerifiedAt();
        }

        $eloquentUser->save();

        return $this->mapper->toDomain($eloquentUser);
    }

    public function delete(User $user): void
    {
        if ($user->getId() === null) {
            return;
        }

        EloquentUser::destroy($user->getId());
    }

    public function paginate(int $perPage = 10): array
    {
        $paginator = EloquentUser::latest()->paginate($perPage);

        $domainUsers = $paginator->getCollection()->map(function ($eloquentUser) {
            return $this->mapper->toDomain($eloquentUser);
        })->toArray();

        return [
            'items' => $domainUsers,
            'total' => $paginator->total(),
            'current_page' => $paginator->currentPage(),
            'per_page' => $paginator->perPage(),
        ];
    }

    public function findEloquentById(int $id): ?EloquentUser
    {
        return EloquentUser::find($id);
    }

    public function findAuthenticatableById(int $id): ?Authenticatable
    {
        return EloquentUser::find($id);
    }

    public function getEloquentPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return EloquentUser::latest()->paginate($perPage);
    }

    public function getTotalCount(): int
    {
        return EloquentUser::count();
    }
}

