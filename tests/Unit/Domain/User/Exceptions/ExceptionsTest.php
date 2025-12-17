<?php

namespace Tests\Unit\Domain\User\Exceptions;

use App\Domain\User\Exceptions\InvalidCredentialsException;
use App\Domain\User\Exceptions\UserAlreadyExistsException;
use App\Domain\User\Exceptions\UserNotFoundException;
use PHPUnit\Framework\TestCase;

class ExceptionsTest extends TestCase
{
    public function test_invalid_credentials_exception(): void
    {
        $exception = new InvalidCredentialsException();

        $this->assertInstanceOf(\Exception::class, $exception);
        $this->assertNotEmpty($exception->getMessage());
    }

    public function test_user_not_found_exception_with_id(): void
    {
        $exception = UserNotFoundException::withId(123);

        $this->assertInstanceOf(\Exception::class, $exception);
        $this->assertStringContainsString('123', $exception->getMessage());
    }

    public function test_user_already_exists_exception_with_email(): void
    {
        $exception = UserAlreadyExistsException::withEmail('test@example.com');

        $this->assertInstanceOf(\Exception::class, $exception);
        $this->assertStringContainsString('test@example.com', $exception->getMessage());
    }
}
