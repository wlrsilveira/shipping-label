<?php

namespace Tests\Unit\Domain\ShippingLabel\Exceptions;

use App\Domain\ShippingLabel\Exceptions\ShippingLabelNotFoundException;
use App\Domain\ShippingLabel\Exceptions\UnauthorizedAccessException;
use PHPUnit\Framework\TestCase;

class ExceptionsTest extends TestCase
{
    public function test_shipping_label_not_found_exception_with_id(): void
    {
        $exception = ShippingLabelNotFoundException::withId(123);

        $this->assertInstanceOf(\Exception::class, $exception);
        $this->assertStringContainsString('123', $exception->getMessage());
    }

    public function test_unauthorized_access_exception_to_shipping_label(): void
    {
        $exception = UnauthorizedAccessException::toShippingLabel(123, 456);

        $this->assertInstanceOf(\Exception::class, $exception);
        $this->assertStringContainsString('123', $exception->getMessage());
        $this->assertStringContainsString('456', $exception->getMessage());
    }
}
