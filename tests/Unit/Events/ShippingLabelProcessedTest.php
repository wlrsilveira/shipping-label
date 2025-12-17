<?php

namespace Tests\Unit\Events;

use App\Events\ShippingLabelProcessed;
use App\Models\ShippingLabel;
use PHPUnit\Framework\TestCase;

class ShippingLabelProcessedTest extends TestCase
{
    public function test_event_contains_shipping_label(): void
    {
        $label = new ShippingLabel();

        $event = new ShippingLabelProcessed($label);

        $this->assertEquals($label, $event->shippingLabel);
    }

    public function test_event_broadcasts_with_correct_name(): void
    {
        $label = new ShippingLabel();

        $event = new ShippingLabelProcessed($label);

        $this->assertEquals('shipping-label.processed', $event->broadcastAs());
    }
}
