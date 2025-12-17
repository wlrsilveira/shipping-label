<?php

namespace App\Domain\ShippingLabel\Providers;

use App\Domain\ShippingLabel\ValueObjects\Address;
use App\Domain\ShippingLabel\ValueObjects\Package;

interface ShippingProviderInterface
{
    public function createShipment(
        Address $fromAddress,
        Address $toAddress,
        Package $package
    ): array;

    public function cancelShipment(string $shipmentId): bool;
}

