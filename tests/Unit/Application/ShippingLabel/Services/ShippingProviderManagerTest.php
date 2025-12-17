<?php

namespace Tests\Unit\Application\ShippingLabel\Services;

use App\Application\ShippingLabel\Services\ShippingProviderManager;
use App\Domain\ShippingLabel\Providers\ShippingProviderInterface;
use PHPUnit\Framework\TestCase;

class ShippingProviderManagerTest extends TestCase
{
    public function test_can_register_provider(): void
    {
        $manager = new ShippingProviderManager();
        $provider = $this->createMock(ShippingProviderInterface::class);

        $manager->register($provider);

        $providers = $manager->getProviders();

        $this->assertCount(1, $providers);
        $this->assertEquals($provider, $providers[0]);
    }

    public function test_can_register_multiple_providers(): void
    {
        $manager = new ShippingProviderManager();
        $provider1 = $this->createMock(ShippingProviderInterface::class);
        $provider2 = $this->createMock(ShippingProviderInterface::class);

        $manager->register($provider1);
        $manager->register($provider2);

        $providers = $manager->getProviders();

        $this->assertCount(2, $providers);
        $this->assertEquals($provider1, $providers[0]);
        $this->assertEquals($provider2, $providers[1]);
    }

    public function test_returns_empty_array_when_there_are_no_providers(): void
    {
        $manager = new ShippingProviderManager();

        $providers = $manager->getProviders();

        $this->assertIsArray($providers);
        $this->assertCount(0, $providers);
    }
}
