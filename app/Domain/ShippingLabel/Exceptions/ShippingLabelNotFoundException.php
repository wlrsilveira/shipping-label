<?php

namespace App\Domain\ShippingLabel\Exceptions;

class ShippingLabelNotFoundException extends DomainException
{
    protected int $statusCode = 404;
    protected string $errorCode = 'SHIPPING_LABEL_NOT_FOUND';

    public static function withId(int $id): self
    {
        return new self("Shipping label with ID {$id} not found");
    }

    public static function withTrackingCode(string $trackingCode): self
    {
        return new self("Shipping label with tracking code {$trackingCode} not found");
    }
}

