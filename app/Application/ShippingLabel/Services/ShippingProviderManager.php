<?php

namespace App\Application\ShippingLabel\Services;

use App\Domain\ShippingLabel\Providers\ShippingProviderInterface;

class ShippingProviderManager
{
    private array $providers = [];

    public function register(ShippingProviderInterface $provider): void
    {
        $this->providers[] = $provider;
    }

    public function getProviders(): array
    {
        return $this->providers;
    }
}

