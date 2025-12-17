<?php

namespace App\Events;

use App\Models\ShippingLabel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ShippingLabelProcessed implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public ShippingLabel $shippingLabel
    ) {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.'.$this->shippingLabel->user_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'shipping-label.processed';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->shippingLabel->id,
            'status' => $this->shippingLabel->status,
            'tracking_code' => $this->shippingLabel->tracking_code,
            'carrier' => $this->shippingLabel->carrier,
            'label_url' => $this->shippingLabel->label_url,
            'updated_at' => $this->shippingLabel->updated_at?->toISOString(),
        ];
    }
}


