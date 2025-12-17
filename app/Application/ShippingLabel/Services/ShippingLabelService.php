<?php

namespace App\Application\ShippingLabel\Services;

use App\Application\ShippingLabel\DTOs\CreateShippingLabelDTO;
use App\Domain\ShippingLabel\Entities\ShippingLabel;
use App\Domain\ShippingLabel\Exceptions\ShippingLabelNotFoundException;
use App\Domain\ShippingLabel\Factories\AddressFactory;
use App\Domain\ShippingLabel\Factories\PackageFactory;
use App\Domain\ShippingLabel\Repositories\ShippingLabelRepositoryInterface;
use App\Domain\ShippingLabel\ValueObjects\ShippingLabelStatus;
use App\Jobs\ProcessShippingLabelJob;
use Illuminate\Pagination\LengthAwarePaginator;

class ShippingLabelService
{
    public function __construct(
        private ShippingLabelRepositoryInterface $repository,
        private ShippingProviderManager $providerManager
    ) {
    }

    public function createShippingLabel(CreateShippingLabelDTO $dto, int $userId): ShippingLabel
    {
        $fromAddress = AddressFactory::fromArray($dto->fromAddress);
        $toAddress = AddressFactory::fromArray($dto->toAddress);
        $package = PackageFactory::fromArray($dto->package);

        $label = ShippingLabel::create(
            userId: $userId,
            fromAddress: $fromAddress,
            toAddress: $toAddress,
            package: $package
        );

        $label = $this->repository->save($label);

        if ($label->getId() === null) {
            throw new \RuntimeException('Shipping label ID is null after save');
        }

        ProcessShippingLabelJob::dispatch($label->getId());

        return $label;
    }

    public function getUserShippingLabels(
        int $userId,
        int $perPage = 15,
        ?string $status = null
    ): LengthAwarePaginator {
        $result = $this->repository->paginate($perPage, $userId, $status);

        $items = collect($result['items']);

        $paginator = new LengthAwarePaginator(
            $items,
            $result['total'],
            $result['per_page'],
            $result['current_page'],
            [
                'path' => request()->url(),
                'pageName' => 'page',
            ]
        );

        return $paginator->withPath(request()->url())->withQueryString();
    }

    public function getShippingLabelById(int $labelId, int $userId): ShippingLabel
    {
        $label = $this->repository->findByIdAndUserId($labelId, $userId);

        if (!$label) {
            throw ShippingLabelNotFoundException::withId($labelId);
        }

        $label->ensureOwnedBy($userId);

        return $label;
    }

    public function deleteShippingLabel(int $labelId, int $userId): void
    {
        $label = $this->repository->findByIdAndUserId($labelId, $userId);

        if (!$label) {
            throw ShippingLabelNotFoundException::withId($labelId);
        }

        $label->ensureOwnedBy($userId);

        if ($label->getExternalShipmentId()) {
            foreach ($this->providerManager->getProviders() as $provider) {
                try {
                    $provider->cancelShipment($label->getExternalShipmentId());
                    break;
                } catch (\Exception $e) {
                    continue;
                }
            }
        }

        $this->repository->delete($label);
    }

    public function getUserStatsByStatus(int $userId): array
    {
        $result = $this->repository->getStatsByStatus($userId);

        $stats = [];
        foreach (ShippingLabelStatus::cases() as $status) {
            $stats[$status->value] = [
                'count' => $result[$status->value] ?? 0,
                'label' => $status->getLabel(),
            ];
        }

        return $stats;
    }
}

