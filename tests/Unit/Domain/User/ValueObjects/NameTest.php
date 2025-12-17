<?php

namespace Tests\Unit\Domain\User\ValueObjects;

use App\Domain\User\ValueObjects\Name;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class NameTest extends TestCase
{
    public function test_can_create_valid_name(): void
    {
        $name = new Name('John Doe');

        $this->assertEquals('John Doe', $name->getValue());
    }

    public function test_can_convert_name_to_string(): void
    {
        $name = new Name('John Doe');

        $this->assertEquals('John Doe', (string) $name);
    }

    public function test_throws_exception_for_empty_name(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Name cannot be empty');

        new Name('');
    }

    public function test_throws_exception_for_name_with_only_spaces(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Name cannot be empty');

        new Name('   ');
    }

    public function test_throws_exception_for_name_too_long(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Name cannot exceed 255 characters');

        new Name(str_repeat('a', 256));
    }
}
