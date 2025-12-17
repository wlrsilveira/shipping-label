<?php

namespace Tests\Unit\Events;

use App\Events\ShippingLabelProcessed;
use App\Models\ShippingLabel;
use Illuminate\Broadcasting\PrivateChannel;
use PHPUnit\Framework\TestCase;

class ShippingLabelProcessedTest extends TestCase
{
    public function test_evento_contem_shipping_label(): void
    {
        $label = new ShippingLabel();

        $event = new ShippingLabelProcessed($label);

        $this->assertEquals($label, $event->shippingLabel);
    }

    public function test_evento_transmite_com_nome_correto(): void
    {
        $label = new ShippingLabel();

        $event = new ShippingLabelProcessed($label);

        $this->assertEquals('shipping-label.processed', $event->broadcastAs());
    }
}

