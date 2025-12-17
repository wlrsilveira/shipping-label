<?php

namespace Tests\Unit\Domain\ShippingLabel\ValueObjects;

use App\Domain\ShippingLabel\ValueObjects\ShippingLabelStatus;
use PHPUnit\Framework\TestCase;

class ShippingLabelStatusTest extends TestCase
{
    public function test_pode_criar_status_pending(): void
    {
        $status = ShippingLabelStatus::PENDING;

        $this->assertEquals('pending', $status->value);
    }

    public function test_pode_verificar_se_e_pending(): void
    {
        $this->assertTrue(ShippingLabelStatus::PENDING->isPending());
        $this->assertFalse(ShippingLabelStatus::CREATED->isPending());
        $this->assertFalse(ShippingLabelStatus::FAILED->isPending());
        $this->assertFalse(ShippingLabelStatus::CANCELLED->isPending());
    }

    public function test_pode_verificar_se_e_created(): void
    {
        $this->assertTrue(ShippingLabelStatus::CREATED->isCreated());
        $this->assertFalse(ShippingLabelStatus::PENDING->isCreated());
        $this->assertFalse(ShippingLabelStatus::FAILED->isCreated());
        $this->assertFalse(ShippingLabelStatus::CANCELLED->isCreated());
    }

    public function test_pode_verificar_se_e_failed(): void
    {
        $this->assertTrue(ShippingLabelStatus::FAILED->isFailed());
        $this->assertFalse(ShippingLabelStatus::PENDING->isFailed());
        $this->assertFalse(ShippingLabelStatus::CREATED->isFailed());
        $this->assertFalse(ShippingLabelStatus::CANCELLED->isFailed());
    }

    public function test_pode_verificar_se_e_cancelled(): void
    {
        $this->assertTrue(ShippingLabelStatus::CANCELLED->isCancelled());
        $this->assertFalse(ShippingLabelStatus::PENDING->isCancelled());
        $this->assertFalse(ShippingLabelStatus::CREATED->isCancelled());
        $this->assertFalse(ShippingLabelStatus::FAILED->isCancelled());
    }

    public function test_pode_verificar_se_pode_ser_cancelado(): void
    {
        $this->assertTrue(ShippingLabelStatus::PENDING->canBeCancelled());
        $this->assertTrue(ShippingLabelStatus::CREATED->canBeCancelled());
        $this->assertFalse(ShippingLabelStatus::FAILED->canBeCancelled());
        $this->assertFalse(ShippingLabelStatus::CANCELLED->canBeCancelled());
    }

    public function test_pode_verificar_se_tem_label(): void
    {
        $this->assertTrue(ShippingLabelStatus::CREATED->hasLabel());
        $this->assertFalse(ShippingLabelStatus::PENDING->hasLabel());
        $this->assertFalse(ShippingLabelStatus::FAILED->hasLabel());
        $this->assertFalse(ShippingLabelStatus::CANCELLED->hasLabel());
    }

    public function test_pode_obter_label_formatada(): void
    {
        $this->assertEquals('Pending', ShippingLabelStatus::PENDING->getLabel());
        $this->assertEquals('Created', ShippingLabelStatus::CREATED->getLabel());
        $this->assertEquals('Failed', ShippingLabelStatus::FAILED->getLabel());
        $this->assertEquals('Cancelled', ShippingLabelStatus::CANCELLED->getLabel());
    }
}

