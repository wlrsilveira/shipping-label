<?php

namespace App\Jobs;

use App\Application\ShippingLabel\Pipelines\CreateShipmentPipe;
use App\Application\ShippingLabel\Services\ShippingProviderManager;
use App\Domain\ShippingLabel\Repositories\ShippingLabelRepositoryInterface;
use App\Events\ShippingLabelProcessed;
use App\Models\ShippingLabel as EloquentShippingLabel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessShippingLabelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        private int $shippingLabelId
    ) {
    }

    public function handle(
        ShippingLabelRepositoryInterface $repository,
        ShippingProviderManager $providerManager
    ): void {
        $label = $repository->findById($this->shippingLabelId);

        if (!$label || !$label->isPending()) {
            return;
        }

        $updatedLabel = app(Pipeline::class)
            ->send($label)
            ->through([
                new CreateShipmentPipe(
                    $providerManager->getProviders(),
                    $repository
                ),
            ])
            ->thenReturn();

        $this->broadcastUpdate($updatedLabel->getId());
    }

    public function failed(?\Throwable $exception): void
    {
        $repository = app(ShippingLabelRepositoryInterface::class);
        $label = $repository->findById($this->shippingLabelId);

        if ($label && $label->isPending()) {
            $label->markAsFailed();
            $repository->save($label);
            $this->broadcastUpdate($this->shippingLabelId);
        }
    }

    private function broadcastUpdate(int $labelId): void
    {
        $eloquentLabel = EloquentShippingLabel::find($labelId);

        if ($eloquentLabel) {
            event(new ShippingLabelProcessed($eloquentLabel));
        }
    }
}

