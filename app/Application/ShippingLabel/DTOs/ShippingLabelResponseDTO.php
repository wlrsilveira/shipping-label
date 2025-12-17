<?php

namespace App\Application\ShippingLabel\DTOs;

use App\Domain\ShippingLabel\Entities\ShippingLabel;

final readonly class ShippingLabelResponseDTO
{
    public function __construct(
        public int $id,
        public int $userId,
        public array $fromAddress,
        public array $toAddress,
        public array $package,
        public ?string $externalShipmentId,
        public ?string $provider,
        public ?string $labelUrl,
        public ?string $trackingCode,
        public string $status,
        public ?string $carrier,
        public ?string $service,
        public ?float $rate,
        public ?string $createdAt,
        public ?string $updatedAt,
    ) {
    }

    public static function fromDomain(ShippingLabel $label): self
    {
        return new self(
            id: $label->getId() ?? 0,
            userId: $label->getUserId(),
            fromAddress: $label->getFromAddress()->toArray(),
            toAddress: $label->getToAddress()->toArray(),
            package: $label->getPackage()->toArray(),
            externalShipmentId: $label->getExternalShipmentId(),
            provider: $label->getProvider(),
            labelUrl: $label->getLabelUrl(),
            trackingCode: $label->getTrackingCode(),
            status: $label->getStatusValue(),
            carrier: $label->getCarrier(),
            service: $label->getService(),
            rate: $label->getRate(),
            createdAt: $label->getCreatedAt()?->format('Y-m-d H:i:s'),
            updatedAt: $label->getUpdatedAt()?->format('Y-m-d H:i:s'),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'from_address' => $this->fromAddress,
            'to_address' => $this->toAddress,
            'package' => $this->package,
            'external_shipment_id' => $this->externalShipmentId,
            'provider' => $this->provider,
            'label_url' => $this->labelUrl,
            'tracking_code' => $this->trackingCode,
            'status' => $this->status,
            'carrier' => $this->carrier,
            'service' => $this->service,
            'rate' => $this->rate,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}

