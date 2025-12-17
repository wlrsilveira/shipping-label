<?php

namespace App\Infrastructure\ShippingLabel\Repositories;

use App\Domain\ShippingLabel\Entities\ShippingLabel;
use App\Domain\ShippingLabel\Repositories\ShippingLabelRepositoryInterface;
use App\Infrastructure\ShippingLabel\Mappers\ShippingLabelMapper;
use App\Models\ShippingLabel as EloquentShippingLabel;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentShippingLabelRepository implements ShippingLabelRepositoryInterface
{
    public function __construct(
        private ShippingLabelMapper $mapper
    ) {
    }

    public function findById(int $id): ?ShippingLabel
    {
        $eloquentLabel = EloquentShippingLabel::find($id);

        return $eloquentLabel ? $this->mapper->toDomain($eloquentLabel) : null;
    }

    public function findByIdAndUserId(int $id, int $userId): ?ShippingLabel
    {
        $eloquentLabel = EloquentShippingLabel::where('id', $id)
            ->where('user_id', $userId)
            ->first();

        return $eloquentLabel ? $this->mapper->toDomain($eloquentLabel) : null;
    }

    public function findByTrackingCode(string $trackingCode): ?ShippingLabel
    {
        $eloquentLabel = EloquentShippingLabel::where('tracking_code', $trackingCode)->first();

        return $eloquentLabel ? $this->mapper->toDomain($eloquentLabel) : null;
    }

    public function findByUserId(int $userId): array
    {
        $eloquentLabels = EloquentShippingLabel::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return $eloquentLabels->map(function ($eloquentLabel) {
            return $this->mapper->toDomain($eloquentLabel);
        })->toArray();
    }

    public function save(ShippingLabel $label): ShippingLabel
    {
        $eloquentLabel = $this->mapper->toEloquent($label);
        $eloquentLabel->save();

        return $this->mapper->toDomain($eloquentLabel);
    }

    public function delete(ShippingLabel $label): void
    {
        if ($label->getId() === null) {
            return;
        }

        EloquentShippingLabel::destroy($label->getId());
    }

    public function paginate(int $perPage = 15, ?int $userId = null, ?string $status = null): array
    {
        $query = EloquentShippingLabel::query();

        if ($userId !== null) {
            $query->where('user_id', $userId);
        }

        if ($status !== null) {
            $query->where('status', $status);
        }

        $paginator = $query->orderBy('created_at', 'desc')->paginate($perPage);

        $domainLabels = $paginator->getCollection()->map(function ($eloquentLabel) {
            return $this->mapper->toDomain($eloquentLabel);
        })->toArray();

        return [
            'items' => $domainLabels,
            'total' => $paginator->total(),
            'current_page' => $paginator->currentPage(),
            'per_page' => $paginator->perPage(),
            'last_page' => $paginator->lastPage(),
        ];
    }

    public function existsByExternalShipmentId(string $shipmentId, ?string $provider = null): bool
    {
        $query = EloquentShippingLabel::where('external_shipment_id', $shipmentId);

        if ($provider !== null) {
            $query->where('provider', $provider);
        }

        return $query->exists();
    }
}

