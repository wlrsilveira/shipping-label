<?php

namespace Tests\Unit\Domain\ShippingLabel\Entities;

use App\Domain\ShippingLabel\Entities\ShippingLabel;
use App\Domain\ShippingLabel\Exceptions\UnauthorizedAccessException;
use App\Domain\ShippingLabel\ValueObjects\Address;
use App\Domain\ShippingLabel\ValueObjects\Country;
use App\Domain\ShippingLabel\ValueObjects\Package;
use App\Domain\ShippingLabel\ValueObjects\ShippingLabelStatus;
use App\Domain\ShippingLabel\ValueObjects\USState;
use PHPUnit\Framework\TestCase;

class ShippingLabelTest extends TestCase
{
    private function createAddress(): Address
    {
        return new Address(
            street1: '123 Main St',
            street2: null,
            city: 'Los Angeles',
            state: USState::CALIFORNIA,
            zipCode: '90001',
            country: Country::UNITED_STATES
        );
    }

    private function createPackage(): Package
    {
        return new Package(
            weight: 10.0,
            length: 12.0,
            width: 8.0,
            height: 6.0
        );
    }

    public function test_can_create_shipping_label(): void
    {
        $label = ShippingLabel::create(
            userId: 1,
            fromAddress: $this->createAddress(),
            toAddress: $this->createAddress(),
            package: $this->createPackage()
        );

        $this->assertNull($label->getId());
        $this->assertEquals(1, $label->getUserId());
        $this->assertEquals(ShippingLabelStatus::PENDING, $label->getStatus());
        $this->assertInstanceOf(\DateTimeImmutable::class, $label->getCreatedAt());
    }

    public function test_can_mark_as_created(): void
    {
        $label = ShippingLabel::create(
            userId: 1,
            fromAddress: $this->createAddress(),
            toAddress: $this->createAddress(),
            package: $this->createPackage()
        );

        $label->markAsCreated(
            externalShipmentId: 'ext_123',
            provider: 'EasyPost',
            labelUrl: 'https://example.com/label.pdf',
            trackingCode: 'TRACK123',
            carrier: 'USPS',
            service: 'First',
            rate: 10.50
        );

        $this->assertEquals('ext_123', $label->getExternalShipmentId());
        $this->assertEquals('EasyPost', $label->getProvider());
        $this->assertEquals('https://example.com/label.pdf', $label->getLabelUrl());
        $this->assertEquals('TRACK123', $label->getTrackingCode());
        $this->assertEquals('USPS', $label->getCarrier());
        $this->assertEquals('First', $label->getService());
        $this->assertEquals(10.50, $label->getRate());
        $this->assertEquals(ShippingLabelStatus::CREATED, $label->getStatus());
        $this->assertTrue($label->isCreated());
    }

    public function test_can_mark_as_failed(): void
    {
        $label = ShippingLabel::create(
            userId: 1,
            fromAddress: $this->createAddress(),
            toAddress: $this->createAddress(),
            package: $this->createPackage()
        );

        $label->markAsFailed();

        $this->assertEquals(ShippingLabelStatus::FAILED, $label->getStatus());
        $this->assertTrue($label->isFailed());
    }

    public function test_can_cancel_label(): void
    {
        $label = ShippingLabel::create(
            userId: 1,
            fromAddress: $this->createAddress(),
            toAddress: $this->createAddress(),
            package: $this->createPackage()
        );

        $label->cancel();

        $this->assertEquals(ShippingLabelStatus::CANCELLED, $label->getStatus());
        $this->assertTrue($label->isCancelled());
    }

    public function test_can_check_if_is_pending(): void
    {
        $label = ShippingLabel::create(
            userId: 1,
            fromAddress: $this->createAddress(),
            toAddress: $this->createAddress(),
            package: $this->createPackage()
        );

        $this->assertTrue($label->isPending());
    }

    public function test_can_check_if_has_label(): void
    {
        $label = ShippingLabel::create(
            userId: 1,
            fromAddress: $this->createAddress(),
            toAddress: $this->createAddress(),
            package: $this->createPackage()
        );

        $this->assertFalse($label->hasLabel());

        $label->markAsCreated(
            externalShipmentId: 'ext_123',
            provider: 'EasyPost',
            labelUrl: 'https://example.com/label.pdf',
            trackingCode: 'TRACK123',
            carrier: 'USPS',
            service: 'First',
            rate: 10.50
        );

        $this->assertTrue($label->hasLabel());
    }

    public function test_can_check_if_can_be_cancelled(): void
    {
        $label = ShippingLabel::create(
            userId: 1,
            fromAddress: $this->createAddress(),
            toAddress: $this->createAddress(),
            package: $this->createPackage()
        );

        $this->assertTrue($label->canBeCancelled());

        $label->markAsFailed();
        $this->assertFalse($label->canBeCancelled());
    }

    public function test_can_check_ownership(): void
    {
        $label = ShippingLabel::create(
            userId: 1,
            fromAddress: $this->createAddress(),
            toAddress: $this->createAddress(),
            package: $this->createPackage()
        );

        $this->assertTrue($label->isOwnedBy(1));
        $this->assertFalse($label->isOwnedBy(2));
    }

    public function test_throws_exception_when_ensuring_incorrect_ownership(): void
    {
        $label = new ShippingLabel(
            id: 1,
            userId: 1,
            fromAddress: $this->createAddress(),
            toAddress: $this->createAddress(),
            package: $this->createPackage()
        );

        $this->expectException(UnauthorizedAccessException::class);

        $label->ensureOwnedBy(2);
    }

    public function test_does_not_throw_exception_when_ensuring_correct_ownership(): void
    {
        $label = new ShippingLabel(
            id: 1,
            userId: 1,
            fromAddress: $this->createAddress(),
            toAddress: $this->createAddress(),
            package: $this->createPackage()
        );

        $label->ensureOwnedBy(1);

        $this->assertTrue(true);
    }

    public function test_can_update_from_address(): void
    {
        $label = ShippingLabel::create(
            userId: 1,
            fromAddress: $this->createAddress(),
            toAddress: $this->createAddress(),
            package: $this->createPackage()
        );

        $newAddress = new Address(
            street1: '456 Oak Ave',
            street2: null,
            city: 'San Francisco',
            state: USState::CALIFORNIA,
            zipCode: '94102',
            country: Country::UNITED_STATES
        );

        $label->updateFromAddress($newAddress);

        $this->assertEquals('456 Oak Ave', $label->getFromAddress()->getStreet1());
        $this->assertEquals('San Francisco', $label->getFromAddress()->getCity());
    }

    public function test_can_update_to_address(): void
    {
        $label = ShippingLabel::create(
            userId: 1,
            fromAddress: $this->createAddress(),
            toAddress: $this->createAddress(),
            package: $this->createPackage()
        );

        $newAddress = new Address(
            street1: '789 Pine St',
            street2: null,
            city: 'New York',
            state: USState::NEW_YORK,
            zipCode: '10001',
            country: Country::UNITED_STATES
        );

        $label->updateToAddress($newAddress);

        $this->assertEquals('789 Pine St', $label->getToAddress()->getStreet1());
        $this->assertEquals('New York', $label->getToAddress()->getCity());
    }

    public function test_can_update_package(): void
    {
        $label = ShippingLabel::create(
            userId: 1,
            fromAddress: $this->createAddress(),
            toAddress: $this->createAddress(),
            package: $this->createPackage()
        );

        $newPackage = new Package(
            weight: 20.0,
            length: 15.0,
            width: 10.0,
            height: 8.0
        );

        $label->updatePackage($newPackage);

        $this->assertEquals(20.0, $label->getPackage()->getWeight());
        $this->assertEquals(15.0, $label->getPackage()->getLength());
    }

    public function test_can_get_status_as_string(): void
    {
        $label = ShippingLabel::create(
            userId: 1,
            fromAddress: $this->createAddress(),
            toAddress: $this->createAddress(),
            package: $this->createPackage()
        );

        $this->assertEquals('pending', $label->getStatusValue());
    }
}
