<?php

namespace App\Domain\ShippingLabel\ValueObjects;

enum ShippingLabelStatus: string
{
    case PENDING = 'pending';
    case CREATED = 'created';
    case FAILED = 'failed';
    case CANCELLED = 'cancelled';

    public function isPending(): bool
    {
        return $this === self::PENDING;
    }

    public function isCreated(): bool
    {
        return $this === self::CREATED;
    }

    public function isFailed(): bool
    {
        return $this === self::FAILED;
    }

    public function isCancelled(): bool
    {
        return $this === self::CANCELLED;
    }

    public function canBeCancelled(): bool
    {
        return $this === self::PENDING || $this === self::CREATED;
    }

    public function hasLabel(): bool
    {
        return $this === self::CREATED;
    }

    public function getLabel(): string
    {
        return match($this) {
            self::PENDING => 'Pending',
            self::CREATED => 'Created',
            self::FAILED => 'Failed',
            self::CANCELLED => 'Cancelled',
        };
    }
}

