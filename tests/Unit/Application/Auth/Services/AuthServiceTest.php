<?php

namespace Tests\Unit\Application\Auth\Services;

use App\Application\Auth\DTOs\LoginDTO;
use App\Application\Auth\DTOs\RegisterDTO;
use App\Application\Auth\Services\AuthService;
use App\Application\User\DTOs\CreateUserDTO;
use App\Application\User\Services\UserService;
use App\Domain\User\Entities\User as DomainUser;
use App\Domain\User\Exceptions\InvalidCredentialsException;
use App\Domain\User\Exceptions\UserAlreadyExistsException;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\ValueObjects\Email;
use App\Domain\User\ValueObjects\Name;
use App\Infrastructure\User\Repositories\EloquentUserRepository;
use App\Models\User;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Hashing\Hasher;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AuthServiceTest extends TestCase
{
    private UserRepositoryInterface&MockObject $userRepository;
    private EloquentUserRepository&MockObject $eloquentUserRepository;
    private UserService&MockObject $userService;
    private AuthFactory&MockObject $authFactory;
    private MockObject $guard;
    private Hasher&MockObject $hasher;
    private AuthService $authService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->eloquentUserRepository = $this->createMock(EloquentUserRepository::class);
        $this->userService = $this->createMock(UserService::class);
        $this->authFactory = $this->createMock(AuthFactory::class);
        $this->guard = $this->getMockBuilder(Guard::class)
            ->disableOriginalConstructor()
            ->addMethods(['login', 'logout'])
            ->getMockForAbstractClass();
        $this->hasher = $this->createMock(Hasher::class);

        $this->authFactory->method('guard')->willReturn($this->guard);

        $this->authService = new AuthService(
            $this->userRepository,
            $this->eloquentUserRepository,
            $this->userService,
            $this->authFactory,
            $this->hasher
        );
    }

    public function test_can_login_with_valid_credentials(): void
    {
        $email = new Email('test@example.com');
        $dto = new LoginDTO($email, 'password', false);

        $domainUser = new DomainUser(
            id: 1,
            name: new Name('Test User'),
            email: $email,
            hashedPassword: 'hashed_password'
        );

        $eloquentUser = new User();
        $eloquentUser->id = 1;
        $eloquentUser->email = 'test@example.com';

        $this->userRepository
            ->expects($this->once())
            ->method('findByEmail')
            ->with($email)
            ->willReturn($domainUser);

        $this->hasher
            ->expects($this->once())
            ->method('check')
            ->with('password', 'hashed_password')
            ->willReturn(true);

        $this->eloquentUserRepository
            ->expects($this->once())
            ->method('findAuthenticatableById')
            ->with(1)
            ->willReturn($eloquentUser);

        $this->guard
            ->expects($this->once())
            ->method('login')
            ->with($eloquentUser, false);

        $result = $this->authService->login($dto);

        $this->assertEquals($domainUser, $result);
    }

    public function test_throws_exception_when_logging_in_with_invalid_email(): void
    {
        $email = new Email('test@example.com');
        $dto = new LoginDTO($email, 'password', false);

        $this->userRepository
            ->expects($this->once())
            ->method('findByEmail')
            ->with($email)
            ->willReturn(null);

        $this->expectException(InvalidCredentialsException::class);

        $this->authService->login($dto);
    }

    public function test_throws_exception_when_logging_in_with_invalid_password(): void
    {
        $email = new Email('test@example.com');
        $dto = new LoginDTO($email, 'wrong_password', false);

        $domainUser = new DomainUser(
            id: 1,
            name: new Name('Test User'),
            email: $email,
            hashedPassword: 'hashed_password'
        );

        $this->userRepository
            ->expects($this->once())
            ->method('findByEmail')
            ->with($email)
            ->willReturn($domainUser);

        $this->hasher
            ->expects($this->once())
            ->method('check')
            ->with('wrong_password', 'hashed_password')
            ->willReturn(false);

        $this->expectException(InvalidCredentialsException::class);

        $this->authService->login($dto);
    }

    public function test_can_register_user(): void
    {
        $createUserDTO = new CreateUserDTO(
            name: new Name('Test User'),
            email: new Email('test@example.com'),
            plainPassword: 'password'
        );

        $registerDTO = new RegisterDTO($createUserDTO);

        $domainUser = new DomainUser(
            id: 1,
            name: new Name('Test User'),
            email: new Email('test@example.com'),
            hashedPassword: 'hashed_password'
        );

        $eloquentUser = new User();
        $eloquentUser->id = 1;
        $eloquentUser->email = 'test@example.com';

        $this->userRepository
            ->expects($this->once())
            ->method('findByEmail')
            ->with($createUserDTO->email)
            ->willReturn(null);

        $this->userService
            ->expects($this->once())
            ->method('createUser')
            ->with($createUserDTO)
            ->willReturn($domainUser);

        $this->eloquentUserRepository
            ->expects($this->once())
            ->method('findAuthenticatableById')
            ->with(1)
            ->willReturn($eloquentUser);

        $this->guard
            ->expects($this->once())
            ->method('login')
            ->with($eloquentUser);

        $result = $this->authService->register($registerDTO);

        $this->assertEquals($domainUser, $result);
    }

    public function test_throws_exception_when_registering_existing_user(): void
    {
        $createUserDTO = new CreateUserDTO(
            name: new Name('Test User'),
            email: new Email('test@example.com'),
            plainPassword: 'password'
        );

        $registerDTO = new RegisterDTO($createUserDTO);

        $existingUser = new DomainUser(
            id: 1,
            name: new Name('Test User'),
            email: new Email('test@example.com'),
            hashedPassword: 'hashed_password'
        );

        $this->userRepository
            ->expects($this->once())
            ->method('findByEmail')
            ->with($createUserDTO->email)
            ->willReturn($existingUser);

        $this->expectException(UserAlreadyExistsException::class);

        $this->authService->register($registerDTO);
    }

    public function test_can_logout(): void
    {
        $this->guard
            ->expects($this->once())
            ->method('logout');

        $this->authService->logout();
    }
}
