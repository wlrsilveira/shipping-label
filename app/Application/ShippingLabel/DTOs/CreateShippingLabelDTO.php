<?php

namespace App\Application\ShippingLabel\DTOs;

final readonly class CreateShippingLabelDTO
{
    public function __construct(
        public array $fromAddress,
        public array $toAddress,
        public array $package,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            fromAddress: $data['from_address'],
            toAddress: $data['to_address'],
            package: $data['package'],
        );
    }
}

