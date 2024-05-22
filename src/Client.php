<?php

namespace SoftC\Evotor\MobileCashier;

use Http\Client\Exception\HttpException;
use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Message\MessageFactory;
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
    const BASE_URL = 'https://mobilecashier.ru/api';

    protected string $login;
    protected string $password;
    protected HttpClient $client;
    protected RequestFactoryInterface $requestFactory;
    protected StreamFactoryInterface $streamFactory;

    protected ?string $token = null;


    public function __construct(string $login, string $password, ?HttpClient $client = null) {
        $this->login = $login;
        $this->password = $password;
        $this->client = $client ?? HttpClientDiscovery::find();
        $this->requestFactory = Psr17FactoryDiscovery::findRequestFactory();
        $this->streamFactory = Psr17FactoryDiscovery::findStreamFactory();
    }

    public function Login(?string $login = null, ?string $password = null): void {
        $uri = '/v2/authorize?' . http_build_query([
            'login' => $login ?? $this->login,
            'password' => $password ?? $this->password,
        ]);

        $request = $this->makeRequest('GET', $uri);
        $response = $this->sendRequest($request);

        $this->token = $response->getBody()->getContents();
    }

    public function Create(string $userId, Receipt $receipt): void {
        $uri = '/v3/asc/create/' . $userId;

        $request = $this->makeRequest('POST', $uri, [], $receipt);
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

        if (isset($this->token)) {
            $request = $request->withHeader('Authorization', $this->token);
        }
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