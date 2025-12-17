<?php

namespace App\Http\Middleware;

use App\Models\RequestLog;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class LogHttpRequestsMiddleware
{
    public function __invoke(callable $handler): callable
    {
        return function (
            RequestInterface $request,
            array $options
        ) use ($handler) {
            $payload = $this->extractPayload($request);

            $promise = $handler($request, $options);

            return $promise->then(
                function (ResponseInterface $response) use ($request, $payload) {
                    $this->logRequest($request, $response, $payload);
                    return $response;
                },
                function (\Exception $exception) use ($request, $payload) {
                    $this->logRequestError($request, $exception, $payload);
                    throw $exception;
                }
            );
        };
    }

    private function logRequest(
        RequestInterface $request,
        ResponseInterface $response,
        ?array $payload
    ): void {
        try {
            $uri = $request->getUri();
            $domain = $uri->getHost();
            $method = $request->getMethod();
            $path = $uri->getPath();
            $endpoint = "{$method} {$path}";

            $responseBody = $this->extractResponseBody($response);
            $statusCode = $response->getStatusCode();

            RequestLog::create([
                'domain' => $domain,
                'endpoint' => $endpoint,
                'payload' => $payload,
                'response' => $responseBody,
                'status_code' => $statusCode,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to log HTTP request', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function logRequestError(
        RequestInterface $request,
        \Exception $exception,
        ?array $payload
    ): void {
        try {
            $uri = $request->getUri();
            $domain = $uri->getHost();
            $method = $request->getMethod();
            $path = $uri->getPath();
            $endpoint = "{$method} {$path}";

            $statusCode = $this->extractStatusCodeFromException($exception);

            RequestLog::create([
                'domain' => $domain,
                'endpoint' => $endpoint,
                'payload' => $payload,
                'response' => ['error' => $exception->getMessage()],
                'status_code' => $statusCode,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to log HTTP request error', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function extractPayload(RequestInterface $request): ?array
    {
        $body = $request->getBody();

        if ($body->isSeekable()) {
            $body->rewind();
        }

        $contents = (string) $body;

        if ($body->isSeekable()) {
            $body->rewind();
        }

        if (empty($contents)) {
            return null;
        }

        $decoded = json_decode($contents, true);
        return $decoded !== null ? $decoded : ['raw' => $contents];
    }

    private function extractResponseBody(ResponseInterface $response): ?array
    {
        $body = $response->getBody();

        if ($body->isSeekable()) {
            $body->rewind();
        }

        $contents = (string) $body;

        if ($body->isSeekable()) {
            $body->rewind();
        }

        if (empty($contents)) {
            return null;
        }

        $decoded = json_decode($contents, true);
        return $decoded !== null ? $decoded : ['raw' => $contents];
    }

    private function extractStatusCodeFromException(\Exception $exception): int
    {
        if ($exception instanceof \GuzzleHttp\Exception\ClientException) {
            return $exception->getResponse()->getStatusCode();
        }

        if ($exception instanceof \GuzzleHttp\Exception\ServerException) {
            return $exception->getResponse()->getStatusCode();
        }

        return 500;
    }
}

