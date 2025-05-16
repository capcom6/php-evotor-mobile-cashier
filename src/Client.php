<?php

namespace SoftC\Evotor\MobileCashier;

use Http\Client\Exception\HttpException;
use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\Psr17FactoryDiscovery;
use InvalidArgumentException;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use SoftC\Evotor\MobileCashier\Data\Receipt;

/**
 * Description of Client
 *
 * @author capcom
 */
class Client {
    const BASE_URL = 'https://mobcashier-external.evotor.ru/api/v1/external/orders';

    protected string $clientId;
    protected string $apiKey;
    protected HttpClient $client;
    protected RequestFactoryInterface $requestFactory;
    protected StreamFactoryInterface $streamFactory;


    public function __construct(string $clientId, string $apiKey, ?HttpClient $client = null) {
        $this->clientId = $clientId;
        $this->apiKey = $apiKey;
        $this->client = $client ?? HttpClientDiscovery::find();
        $this->requestFactory = Psr17FactoryDiscovery::findRequestFactory();
        $this->streamFactory = Psr17FactoryDiscovery::findStreamFactory();
    }

    public function Login(?string $clientId = null, ?string $apiKey = null): void {
        $this->clientId = $clientId ?? $this->clientId;
        $this->apiKey = $apiKey ?? $this->apiKey;
    }

    public function Create(string $userId, Receipt $receipt): void {
        $uri = '/create/' . urlencode($userId);

        $request = $this->makeRequest(
            'POST',
            $uri,
            [],
            $receipt
        );
        $response = $this->sendRequest($request);

        if ($response->getStatusCode() != 200) {
            throw HttpException::create($request, $response);
        }
    }

    /**
     * @param array<string, string> $headers
     * @param array<mixed>|object|null $payload
     */
    protected function makeRequest(string $method, string $uri, array $headers = [], $payload = null): RequestInterface {
        $payload = isset($payload) ? json_encode($payload) : null;
        if ($payload === false) {
            throw new InvalidArgumentException('Не удалось сериализовать `payload`');
        }

        $request = $this->requestFactory->createRequest(
            $method,
            static::BASE_URL . $uri,
        );

        $request = $request
            ->withHeader('X-Client-Id', $this->clientId)
            ->withHeader('X-Api-Key', $this->apiKey);
        if ($payload !== null) {
            $request = $request
                ->withHeader('Content-Type', 'application/json')
                ->withBody($this->streamFactory->createStream($payload));
        }
        foreach ($headers as $name => $value) {
            $request = $request->withHeader($name, $value);
        }

        return $request;
    }

    protected function sendRequest(RequestInterface $request): ResponseInterface {
        $response = $this->client->sendRequest($request);
        if ($response->getStatusCode() >= 400) {
            throw HttpException::create($request, $response);
        }
        return $response;
    }
}