<?php

namespace App\Domain\ShippingLabel\Factories;

use App\Domain\ShippingLabel\Entities\ShippingLabel;
use App\Domain\ShippingLabel\ValueObjects\Address;
use App\Domain\ShippingLabel\ValueObjects\Package;
use App\Domain\ShippingLabel\ValueObjects\ShippingLabelStatus;

class ShippingLabelFactory
{
    public static function create(
        int $userId,
        Address $fromAddress,
        Address $toAddress,
        Package $package,
        ?int $id = null,
        ?string $easypostShipmentId = null,
        ?string $labelUrl = null,
        ?string $trackingCode = null,
        ShippingLabelStatus $status = ShippingLabelStatus::PENDING,
        ?string $carrier = null,
        ?string $service = null,
        ?float $rate = null,
        ?\DateTimeImmutable $createdAt = null,
        ?\DateTimeImmutable $updatedAt = null,
    ): ShippingLabel {
        return new ShippingLabel(
            id: $id,
            userId: $userId,
            fromAddress: $fromAddress,
            toAddress: $toAddress,
            package: $package,
            easypostShipmentId: $easypostShipmentId,
            labelUrl: $labelUrl,
            trackingCode: $trackingCode,
            status: $status,
            carrier: $carrier,
            service: $service,
            rate: $rate,
            createdAt: $createdAt ?? new \DateTimeImmutable(),
            updatedAt: $updatedAt,
        );
    }

    public static function makeDefault(int $userId = 1): ShippingLabel
    {
        return self::create(
            userId: $userId,
            fromAddress: AddressFactory::makeDefault(),
            toAddress: AddressFactory::makeResidential(),
            package: PackageFactory::makeDefault(),
        );
    }

    public static function makePending(int $userId = 1): ShippingLabel
    {
        return self::create(
            userId: $userId,
            fromAddress: AddressFactory::makeCommercial(),
            toAddress: AddressFactory::makeResidential(),
            package: PackageFactory::makeMedium(),
            status: ShippingLabelStatus::PENDING,
        );
    }

    public static function makeCreated(int $userId = 1): ShippingLabel
    {
        return self::create(
            userId: $userId,
            fromAddress: AddressFactory::makeCommercial(),
            toAddress: AddressFactory::makeResidential(),
            package: PackageFactory::makeDefault(),
            id: 1,
            easypostShipmentId: 'shp_' . bin2hex(random_bytes(12)),
            labelUrl: 'https://easypost-files.s3-us-west-2.amazonaws.com/files/postage_label/12345/label.png',
            trackingCode: '9400111111111111111111',
            status: ShippingLabelStatus::CREATED,
            carrier: 'USPS',
            service: 'Priority',
            rate: 7.33,
        );
    }

    public static function makeFailed(int $userId = 1): ShippingLabel
    {
        return self::create(
            userId: $userId,
            fromAddress: AddressFactory::makeDefault(),
            toAddress: AddressFactory::makeResidential(),
            package: PackageFactory::makeDefault(),
            status: ShippingLabelStatus::FAILED,
        );
    }

    public static function makeCancelled(int $userId = 1): ShippingLabel
    {
        $label = self::makeCreated($userId);
        $label->cancel();
        
        return $label;
    }

    public static function makeWithCustomAddresses(
        int $userId,
        Address $fromAddress,
        Address $toAddress
    ): ShippingLabel {
        return self::create(
            userId: $userId,
            fromAddress: $fromAddress,
            toAddress: $toAddress,
            package: PackageFactory::makeDefault(),
        );
    }

    public static function makeWithCustomPackage(
        int $userId,
        Package $package
    ): ShippingLabel {
        return self::create(
            userId: $userId,
            fromAddress: AddressFactory::makeDefault(),
            toAddress: AddressFactory::makeResidential(),
            package: $package,
        );
    }
}

