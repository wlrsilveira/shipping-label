<?php

namespace App\Application\ShippingLabel\Pipelines;

use App\Domain\ShippingLabel\Exceptions\EasyPostApiException;
use App\Domain\ShippingLabel\Repositories\ShippingLabelRepositoryInterface;
use Closure;

class CreateShipmentPipe
{
    public function __construct(
        private array $providers,
        private ShippingLabelRepositoryInterface $repository
    ) {
    }

    public function handle($label, Closure $next)
    {
        foreach ($this->providers as $provider) {
            try {
                $shipmentData = $provider->createShipment(
                    $label->getFromAddress(),
                    $label->getToAddress(),
                    $label->getPackage()
                );

                $label->markAsCreated(
                    externalShipmentId: $shipmentData['external_shipment_id'],
                    provider: $shipmentData['provider'],
                    labelUrl: $shipmentData['label_url'],
                    trackingCode: $shipmentData['tracking_code'],
                    carrier: $shipmentData['carrier'],
                    service: $shipmentData['service'],
                    rate: $shipmentData['rate']
                );

                $this->repository->save($label);

                return $next($label);
            } catch (EasyPostApiException $e) {
                continue;
            } catch (\Exception $e) {
                continue;
            }
        }

        $label->markAsFailed();
        $this->repository->save($label);

        throw new \RuntimeException('All shipping providers failed to create shipment');
    }
}

