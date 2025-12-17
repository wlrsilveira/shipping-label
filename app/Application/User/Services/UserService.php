<?php

namespace App\Application\User\Services;

use App\Application\User\DTOs\CreateUserDTO;
use App\Application\User\DTOs\UpdateUserDTO;
use App\Domain\User\Entities\User;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Infrastructure\User\Repositories\EloquentUserRepository;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private EloquentUserRepository $eloquentUserRepository,
        private Hasher $hasher
    ) {
    }

    public function createUser(CreateUserDTO $dto): User
    {
        $hashedPassword = $this->hasher->make($dto->plainPassword);

        $user = new User(
            id: null,
            name: $dto->name,
            email: $dto->email,
            hashedPassword: $hashedPassword,
        );

        return $this->userRepository->save($user);
    }

    public function updateUser(int $userId, UpdateUserDTO $dto): User
    {
        $user = $this->userRepository->findById($userId);

        if (!$user) {
            throw UserNotFoundException::withId($userId);
        }

        $user->updateName($dto->name);
        $user->updateEmail($dto->email);

        if ($dto->plainPassword !== null) {
            $hashedPassword = $this->hasher->make($dto->plainPassword);
            $user->updatePassword($hashedPassword);
        }

        return $this->userRepository->save($user);
    }

    public function deleteUser(int $userId): void
    {
        $user = $this->userRepository->findById($userId);

        if (!$user) {
            throw UserNotFoundException::withId($userId);
        }

        $this->userRepository->delete($user);
    }

    public function getUserById(int $userId): ?User
    {
        return $this->userRepository->findById($userId);
    }

    public function getPaginatedUsers(int $perPage = 10): LengthAwarePaginator
    {
        return $this->eloquentUserRepository->getEloquentPaginated($perPage);
    }

    public function getTotalUsersCount(): int
    {
        return $this->eloquentUserRepository->getTotalCount();
    }
}

