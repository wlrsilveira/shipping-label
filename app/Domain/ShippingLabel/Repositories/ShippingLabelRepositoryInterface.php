<?php

namespace App\Domain\ShippingLabel\Repositories;

use App\Domain\ShippingLabel\Entities\ShippingLabel;

interface ShippingLabelRepositoryInterface
{
    public function findById(int $id): ?ShippingLabel;

    public function findByIdAndUserId(int $id, int $userId): ?ShippingLabel;

    public function save(ShippingLabel $label): ShippingLabel;

    public function delete(ShippingLabel $label): void;

    public function paginate(int $perPage = 15, ?int $userId = null, ?string $status = null): array;

    public function getStatsByStatus(int $userId): array;
}

