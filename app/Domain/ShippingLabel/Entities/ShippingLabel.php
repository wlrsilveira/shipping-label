<?php

namespace App\Domain\ShippingLabel\Entities;

use App\Domain\ShippingLabel\ValueObjects\Address;
use App\Domain\ShippingLabel\ValueObjects\Package;
use App\Domain\ShippingLabel\ValueObjects\ShippingLabelStatus;
use App\Domain\ShippingLabel\Exceptions\UnauthorizedAccessException;

class ShippingLabel
{
    public function __construct(
        private ?int $id,
        private int $userId,
        private Address $fromAddress,
        private Address $toAddress,
        private Package $package,
        private ?string $easypostShipmentId = null,
        private ?string $labelUrl = null,
        private ?string $trackingCode = null,
        private ShippingLabelStatus $status = ShippingLabelStatus::PENDING,
        private ?string $carrier = null,
        private ?string $service = null,
        private ?float $rate = null,
        private ?\DateTimeImmutable $createdAt = null,
        private ?\DateTimeImmutable $updatedAt = null,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getFromAddress(): Address
    {
        return $this->fromAddress;
    }

    public function getToAddress(): Address
    {
        return $this->toAddress;
    }

    public function getPackage(): Package
    {
        return $this->package;
    }

    public function getEasypostShipmentId(): ?string
    {
        return $this->easypostShipmentId;
    }

    public function getLabelUrl(): ?string
    {
        return $this->labelUrl;
    }

    public function getTrackingCode(): ?string
    {
        return $this->trackingCode;
    }

    public function getStatus(): ShippingLabelStatus
    {
        return $this->status;
    }

    public function getStatusValue(): string
    {
        return $this->status->value;
    }

    public function getCarrier(): ?string
    {
        return $this->carrier;
    }

    public function getService(): ?string
    {
        return $this->service;
    }

    public function getRate(): ?float
    {
        return $this->rate;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function markAsCreated(
        string $easypostShipmentId,
        string $labelUrl,
        string $trackingCode,
        string $carrier,
        string $service,
        float $rate
    ): void {
        $this->easypostShipmentId = $easypostShipmentId;
        $this->labelUrl = $labelUrl;
        $this->trackingCode = $trackingCode;
        $this->carrier = $carrier;
        $this->service = $service;
        $this->rate = $rate;
        $this->status = ShippingLabelStatus::CREATED;
    }

    public function markAsFailed(): void
    {
        $this->status = ShippingLabelStatus::FAILED;
    }

    public function cancel(): void
    {
        if ($this->status === ShippingLabelStatus::CANCELLED) {
            return;
        }

        $this->status = ShippingLabelStatus::CANCELLED;
    }

    public function isOwnedBy(int $userId): bool
    {
        return $this->userId === $userId;
    }

    public function ensureOwnedBy(int $userId): void
    {
        if (!$this->isOwnedBy($userId)) {
            throw UnauthorizedAccessException::toShippingLabel($this->id ?? 0, $userId);
        }
    }

    public function isPending(): bool
    {
        return $this->status->isPending();
    }

    public function isCreated(): bool
    {
        return $this->status->isCreated();
    }

    public function isFailed(): bool
    {
        return $this->status->isFailed();
    }

    public function isCancelled(): bool
    {
        return $this->status->isCancelled();
    }

    public function hasLabel(): bool
    {
        return $this->status->hasLabel() && $this->labelUrl !== null && $this->trackingCode !== null;
    }

    public function canBeCancelled(): bool
    {
        return $this->status->canBeCancelled();
    }

    public function updateFromAddress(Address $address): void
    {
        $this->fromAddress = $address;
    }

    public function updateToAddress(Address $address): void
    {
        $this->toAddress = $address;
    }

    public function updatePackage(Package $package): void
    {
        $this->package = $package;
    }

    public static function create(
        int $userId,
        Address $fromAddress,
        Address $toAddress,
        Package $package
    ): self {
        return new self(
            id: null,
            userId: $userId,
            fromAddress: $fromAddress,
            toAddress: $toAddress,
            package: $package,
            createdAt: new \DateTimeImmutable(),
        );
    }
}

