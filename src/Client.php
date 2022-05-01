<?php

namespace SoftC\Evotor\MobileCashier;

use Http\Client\Exception\HttpException;
use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\MessageFactory;
use InvalidArgumentException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Description of Client
 *
 * @author capcom
 */
class Client
{
    const BASE_URL = 'https://qrstats.ru:8088/';

    private string $userId;
    /**
     * Default headers
     * @var array<string, string>
     */
    private array $headers;

    private HttpClient $client;
    private MessageFactory $messageFactory;


    public function __construct(string $userId, ?HttpClient $client = null)
    {
        $this->userId = $userId;
        $this->headers = [
            'userId' => $this->userId,
            'Content-Type' => 'application/json',
        ];

        $this->client = $client ?? HttpClientDiscovery::find();
        $this->messageFactory = MessageFactoryDiscovery::find();
    }

    public function create(Data\Receipt $receipt): bool
    {
        $uri = '/api/v1/receipt/pre/create';

        $request = $this->makeRequest('POST', $uri, $this->headers, $receipt);
        $response = $this->sendRequest($request);

        return $response->getStatusCode() === 200;
    }

    /**
     * @param array<string, string> $headers
     * @param array<mixed>|object|null $payload
     */
    protected function makeRequest(string $method, string $uri, array $headers = [], $payload = null): RequestInterface
    {
        $payload = isset($payload) ? json_encode($payload) : null;
        if ($payload === false) {
            throw new InvalidArgumentException('Не удалось сериализовать `payload`');
        }

        return $this->messageFactory->createRequest(
            $method,
            static::BASE_URL . $uri,
            $headers,
            $payload
        );
    }

    protected function sendRequest(RequestInterface $request): ResponseInterface
    {
        $response = $this->client->sendRequest($request);
        if ($response->getStatusCode() >= 400) {
            throw HttpException::create($request, $response);
        }
        return $response;
    }
}
