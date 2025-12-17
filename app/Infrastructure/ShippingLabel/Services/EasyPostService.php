<?php

namespace App\Infrastructure\ShippingLabel\Services;

use App\Domain\ShippingLabel\ValueObjects\Address;
use App\Domain\ShippingLabel\ValueObjects\Package;
use App\Domain\ShippingLabel\Exceptions\EasyPostApiException;
use App\Domain\ShippingLabel\Providers\ShippingProviderInterface;
use App\Http\Middleware\LogHttpRequestsMiddleware;
use EasyPost\EasyPostClient;
use EasyPost\Exception\Api\ApiException;
use GuzzleHttp\HandlerStack;

class EasyPostService implements ShippingProviderInterface
{
    private EasyPostClient $client;

    public function __construct()
    {
        $apiKey = config('services.easypost.api_key');

        if (empty($apiKey)) {
            throw new \RuntimeException('EasyPost API key is not configured');
        }

        $this->client = new EasyPostClient($apiKey);

        $stack = HandlerStack::create();
        $stack->push(new LogHttpRequestsMiddleware());

        $httpClient = new \GuzzleHttp\Client(['handler' => $stack]);

        $reflection = new \ReflectionClass($this->client);
        $property = $reflection->getProperty('httpClient');
        $property->setAccessible(true);
        $property->setValue($this->client, $httpClient);
    }

    public function createShipment(
        Address $fromAddress,
        Address $toAddress,
        Package $package
    ): array {
        $from = $this->mapAddressToEasyPost($fromAddress);
        $to = $this->mapAddressToEasyPost($toAddress);
        $parcel = $this->mapPackageToEasyPost($package);

        $createPayload = [
            'from_address' => $from,
            'to_address' => $to,
            'parcel' => $parcel,
        ];

        try {
            $shipment = $this->client->shipment->create($createPayload);

            if (!$shipment || !$shipment->id) {
                throw EasyPostApiException::shipmentCreationFailed('No shipment ID returned');
            }

            $selectedRate = $this->selectBestRate($shipment);

            if (!$selectedRate) {
                throw EasyPostApiException::shipmentCreationFailed('No rates available');
            }

            $buyPayload = ['rate' => ['id' => $selectedRate->id]];

            $shipment = $this->client->shipment->buy($shipment->id, $buyPayload);

            if (!$shipment->postage_label || !$shipment->postage_label->label_url) {
                throw EasyPostApiException::labelRetrievalFailed($shipment->id, 'Label URL not available');
            }

            return [
                'external_shipment_id' => $shipment->id,
                'provider' => 'easypost',
                'label_url' => $shipment->postage_label->label_url,
                'tracking_code' => $shipment->tracker->tracking_code ?? null,
                'carrier' => $selectedRate->carrier,
                'service' => $selectedRate->service,
                'rate' => (float) $selectedRate->rate,
            ];
        } catch (ApiException $e) {
            throw EasyPostApiException::apiError($e->getMessage(), $e);
        } catch (\Exception $e) {
            throw EasyPostApiException::shipmentCreationFailed($e->getMessage());
        }
    }

    private function mapAddressToEasyPost(Address $address): array
    {
        return [
            'name' => $address->getName(),
            'company' => $address->getCompany(),
            'street1' => $address->getStreet1(),
            'street2' => $address->getStreet2(),
            'city' => $address->getCity(),
            'state' => $address->getState()->value,
            'zip' => $address->getZipCode(),
            'country' => $address->getCountry()->value,
            'phone' => $address->getPhone(),
        ];
    }

    private function mapPackageToEasyPost(Package $package): array
    {
        return [
            'length' => $package->getLength(),
            'width' => $package->getWidth(),
            'height' => $package->getHeight(),
            'weight' => $package->getWeight(),
        ];
    }

    public function cancelShipment(string $shipmentId): bool
    {
        try {
            $refund = $this->client->refund->create(['shipment' => $shipmentId]);

            return $refund && $refund->status === 'submitted';
        } catch (ApiException $e) {
            throw EasyPostApiException::apiError($e->getMessage(), $e);
        } catch (\Exception $e) {
            return false;
        }
    }

    private function selectBestRate(mixed $shipment): ?object
    {
        if (empty($shipment->rates)) {
            return null;
        }

        $rates = $shipment->rates;

        usort($rates, function ($a, $b) {
            return (float) $a->rate <=> (float) $b->rate;
        });

        return $rates[0] ?? null;
    }

}

