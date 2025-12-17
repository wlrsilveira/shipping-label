<?php

namespace App\Domain\ShippingLabel\Exceptions;

class EasyPostApiException extends DomainException
{
    protected int $statusCode = 502;
    protected string $errorCode = 'EASYPOST_API_ERROR';

    public static function apiError(string $message, ?\Throwable $previous = null): self
    {
        return new self("EasyPost API error: {$message}", 0, $previous);
    }

    public static function invalidResponse(string $details = ''): self
    {
        $message = 'Invalid response from EasyPost API';
        if ($details) {
            $message .= ": {$details}";
        }
        return new self($message);
    }

    public static function shipmentCreationFailed(string $reason = ''): self
    {
        $message = 'Failed to create shipment in EasyPost';
        if ($reason) {
            $message .= ": {$reason}";
        }
        return new self($message);
    }

    public static function addressValidationFailed(string $reason = ''): self
    {
        $message = 'Failed to validate address in EasyPost';
        if ($reason) {
            $message .= ": {$reason}";
        }
        return new self($message);
    }

    public static function labelRetrievalFailed(string $shipmentId, string $reason = ''): self
    {
        $message = "Failed to retrieve label for shipment {$shipmentId}";
        if ($reason) {
            $message .= ": {$reason}";
        }
        return new self($message);
    }
}



