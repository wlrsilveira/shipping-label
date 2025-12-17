<?php

namespace App\Infrastructure\ShippingLabel\Mappers;

use App\Domain\ShippingLabel\Entities\ShippingLabel;
use App\Domain\ShippingLabel\ValueObjects\Address;
use App\Domain\ShippingLabel\ValueObjects\Package;
use App\Domain\ShippingLabel\ValueObjects\ShippingLabelStatus;
use App\Models\ShippingLabel as EloquentShippingLabel;

class ShippingLabelMapper
{
    public function toDomain(EloquentShippingLabel $eloquentLabel): ShippingLabel
    {
        return new ShippingLabel(
            id: $eloquentLabel->id,
            userId: $eloquentLabel->user_id,
            fromAddress: Address::fromArray($eloquentLabel->from_address),
            toAddress: Address::fromArray($eloquentLabel->to_address),
            package: Package::fromArray($eloquentLabel->package_data),
            externalShipmentId: $eloquentLabel->external_shipment_id,
            provider: $eloquentLabel->provider,
            labelUrl: $eloquentLabel->label_url,
            trackingCode: $eloquentLabel->tracking_code,
            status: ShippingLabelStatus::from($eloquentLabel->status),
            carrier: $eloquentLabel->carrier,
            service: $eloquentLabel->service,
            rate: $eloquentLabel->rate ? (float) $eloquentLabel->rate : null,
            createdAt: $eloquentLabel->created_at
                ? \DateTimeImmutable::createFromInterface($eloquentLabel->created_at)
                : null,
            updatedAt: $eloquentLabel->updated_at
                ? \DateTimeImmutable::createFromInterface($eloquentLabel->updated_at)
                : null,
        );
    }

    public function toEloquent(ShippingLabel $domainLabel): EloquentShippingLabel
    {
        $eloquentLabel = EloquentShippingLabel::find($domainLabel->getId()) ?? new EloquentShippingLabel();

        $eloquentLabel->user_id = $domainLabel->getUserId();
        $eloquentLabel->from_address = $domainLabel->getFromAddress()->toArray();
        $eloquentLabel->to_address = $domainLabel->getToAddress()->toArray();
        $eloquentLabel->package_data = $domainLabel->getPackage()->toArray();
        $eloquentLabel->external_shipment_id = $domainLabel->getExternalShipmentId();
        $eloquentLabel->provider = $domainLabel->getProvider();
        $eloquentLabel->label_url = $domainLabel->getLabelUrl();
        $eloquentLabel->tracking_code = $domainLabel->getTrackingCode();
        $eloquentLabel->status = $domainLabel->getStatusValue();
        $eloquentLabel->carrier = $domainLabel->getCarrier();
        $eloquentLabel->service = $domainLabel->getService();
        $eloquentLabel->rate = $domainLabel->getRate();

        return $eloquentLabel;
    }
}

