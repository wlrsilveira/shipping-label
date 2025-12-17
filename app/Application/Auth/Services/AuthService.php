<?php

namespace App\Application\Auth\Services;

use App\Application\Auth\DTOs\LoginDTO;
use App\Application\Auth\DTOs\RegisterDTO;
use App\Application\User\Services\UserService;
use App\Domain\User\Entities\User;
use App\Domain\User\Exceptions\InvalidCredentialsException;
use App\Domain\User\Exceptions\UserAlreadyExistsException;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Infrastructure\User\Repositories\EloquentUserRepository;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Contracts\Hashing\Hasher;

class AuthService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private EloquentUserRepository $eloquentUserRepository,
        private UserService $userService,
        private AuthFactory $auth,
        private Hasher $hasher
    ) {
    }

    public function login(LoginDTO $dto): User
    {
        $user = $this->userRepository->findByEmail($dto->email);

        if (!$user || !$this->hasher->check($dto->password, $user->getHashedPassword())) {
            throw new InvalidCredentialsException();
        }

        $authenticatable = $this->eloquentUserRepository->findAuthenticatableById($user->getId());

        if (!$authenticatable) {
            throw UserNotFoundException::withId($user->getId());
        }

        $this->auth->guard()->login(
            $authenticatable,
            $dto->remember
        );

        return $user;
    }

    public function register(RegisterDTO $dto): User
    {
        $existingUser = $this->userRepository->findByEmail($dto->userData->email);
        if ($existingUser) {
            throw UserAlreadyExistsException::withEmail($dto->userData->email->getValue());
        }

        $user = $this->userService->createUser($dto->userData);

        $authenticatable = $this->eloquentUserRepository->findAuthenticatableById($user->getId());

        if (!$authenticatable) {
            throw UserNotFoundException::withId($user->getId());
        }

        $this->auth->guard()->login($authenticatable);

        return $user;
    }

    public function logout(): void
    {
        $this->auth->guard()->logout();
    }
}

