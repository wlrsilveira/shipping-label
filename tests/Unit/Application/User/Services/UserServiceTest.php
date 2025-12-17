<?php

namespace Tests\Unit\Application\User\Services;

use App\Application\User\DTOs\CreateUserDTO;
use App\Application\User\DTOs\UpdateUserDTO;
use App\Application\User\Services\UserService;
use App\Domain\User\Entities\User;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\ValueObjects\Email;
use App\Domain\User\ValueObjects\Name;
use App\Infrastructure\User\Repositories\EloquentUserRepository;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Pagination\LengthAwarePaginator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    private UserRepositoryInterface&MockObject $userRepository;
    private EloquentUserRepository&MockObject $eloquentUserRepository;
    private Hasher&MockObject $hasher;
    private UserService $userService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->eloquentUserRepository = $this->createMock(EloquentUserRepository::class);
        $this->hasher = $this->createMock(Hasher::class);

        $this->userService = new UserService(
            $this->userRepository,
            $this->eloquentUserRepository,
            $this->hasher
        );
    }

    public function test_can_create_user(): void
    {
        $dto = new CreateUserDTO(
            name: new Name('Test User'),
            email: new Email('test@example.com'),
            plainPassword: 'password'
        );

        $this->hasher
            ->expects($this->once())
            ->method('make')
            ->with('password')
            ->willReturn('hashed_password');

        $savedUser = new User(
            id: 1,
            name: new Name('Test User'),
            email: new Email('test@example.com'),
            hashedPassword: 'hashed_password'
        );

        $this->userRepository
            ->expects($this->once())
            ->method('save')
            ->willReturn($savedUser);

        $result = $this->userService->createUser($dto);

        $this->assertEquals(1, $result->getId());
        $this->assertEquals('Test User', $result->getName()->getValue());
        $this->assertEquals('test@example.com', $result->getEmail()->getValue());
    }

    public function test_can_update_user(): void
    {
        $dto = new UpdateUserDTO(
            name: new Name('Updated User'),
            email: new Email('updated@example.com'),
            plainPassword: 'newpassword'
        );

        $existingUser = new User(
            id: 1,
            name: new Name('Test User'),
            email: new Email('test@example.com'),
            hashedPassword: 'old_hashed_password'
        );

        $this->userRepository
            ->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($existingUser);

        $this->hasher
            ->expects($this->once())
            ->method('make')
            ->with('newpassword')
            ->willReturn('new_hashed_password');

        $this->userRepository
            ->expects($this->once())
            ->method('save')
            ->willReturn($existingUser);

        $result = $this->userService->updateUser(1, $dto);

        $this->assertEquals('Updated User', $result->getName()->getValue());
        $this->assertEquals('updated@example.com', $result->getEmail()->getValue());
    }

    public function test_can_update_user_without_password(): void
    {
        $dto = new UpdateUserDTO(
            name: new Name('Updated User'),
            email: new Email('updated@example.com'),
            plainPassword: null
        );

        $existingUser = new User(
            id: 1,
            name: new Name('Test User'),
            email: new Email('test@example.com'),
            hashedPassword: 'old_hashed_password'
        );

        $this->userRepository
            ->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($existingUser);

        $this->hasher
            ->expects($this->never())
            ->method('make');

        $this->userRepository
            ->expects($this->once())
            ->method('save')
            ->willReturn($existingUser);

        $result = $this->userService->updateUser(1, $dto);

        $this->assertEquals('Updated User', $result->getName()->getValue());
    }

    public function test_throws_exception_when_updating_nonexistent_user(): void
    {
        $dto = new UpdateUserDTO(
            name: new Name('Updated User'),
            email: new Email('updated@example.com'),
            plainPassword: null
        );

        $this->userRepository
            ->expects($this->once())
            ->method('findById')
            ->with(999)
            ->willReturn(null);

        $this->expectException(UserNotFoundException::class);

        $this->userService->updateUser(999, $dto);
    }

    public function test_can_delete_user(): void
    {
        $user = new User(
            id: 1,
            name: new Name('Test User'),
            email: new Email('test@example.com'),
            hashedPassword: 'hashed_password'
        );

        $this->userRepository
            ->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($user);

        $this->userRepository
            ->expects($this->once())
            ->method('delete')
            ->with($user);

        $this->userService->deleteUser(1);
    }

    public function test_throws_exception_when_deleting_nonexistent_user(): void
    {
        $this->userRepository
            ->expects($this->once())
            ->method('findById')
            ->with(999)
            ->willReturn(null);

        $this->expectException(UserNotFoundException::class);

        $this->userService->deleteUser(999);
    }

    public function test_can_get_user_by_id(): void
    {
        $user = new User(
            id: 1,
            name: new Name('Test User'),
            email: new Email('test@example.com'),
            hashedPassword: 'hashed_password'
        );

        $this->userRepository
            ->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($user);

        $result = $this->userService->getUserById(1);

        $this->assertEquals($user, $result);
    }

    public function test_can_get_paginated_users(): void
    {
        $paginator = new LengthAwarePaginator([], 0, 10);

        $this->eloquentUserRepository
            ->expects($this->once())
            ->method('getEloquentPaginated')
            ->with(15)
            ->willReturn($paginator);

        $result = $this->userService->getPaginatedUsers(15);

        $this->assertEquals($paginator, $result);
    }

    public function test_can_get_total_users_count(): void
    {
        $this->eloquentUserRepository
            ->expects($this->once())
            ->method('getTotalCount')
            ->willReturn(42);

        $result = $this->userService->getTotalUsersCount();

        $this->assertEquals(42, $result);
    }
}
