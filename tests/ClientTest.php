<?php

namespace SoftC\Evotor\MobileCashier\Tests;

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Mock\Client as MockClient;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use SoftC\Evotor\MobileCashier\Client;
use SoftC\Evotor\MobileCashier\Data\Position;
use SoftC\Evotor\MobileCashier\Data\Receipt;

final class ClientTest extends TestCase {
    private static ResponseFactoryInterface $responseFactory;
    private static StreamFactoryInterface $streamFactory;

    private MockClient $httpClient;
    private Client $client;

    public static function setUpBeforeClass(): void {
        self::$responseFactory = Psr17FactoryDiscovery::findResponseFactory();
        self::$streamFactory = Psr17FactoryDiscovery::findStreamFactory();
    }

    public function setUp(): void {
        $this->httpClient = new MockClient();
        $this->client = new Client('login', 'password', $this->httpClient);
    }

    public function testCreate(): void {
        $uuid = 'uuid';
        $positions = [
            new Position('uuid', 'name', 'шт', 0, '123.45', '1'),
        ];
        $should_print_receipt = false;

        $receipt = new Receipt($uuid, $positions, $should_print_receipt);

        $client = $this->client;

        $client->Create(static::USER_ID, $receipt);

        $this->assertCount(1, $this->httpClient->getRequests());

        $request = $this->httpClient->getLastRequest();
        $this->assertStringEndsWith(static::USER_ID, $request->getUri()->getPath());
        $this->assertJson($request->getBody());
        $this->assertEquals(json_encode($receipt), $request->getBody());
    }

    /**
     * Создает фальшивый ответ
     * @param string $body тело ответа
     * @param int $code код ответа
     * @param array<string, string> $headers заголовки ответа
     * @return ResponseInterface
     * @phpstan-ignore-next-line
     */
    private function mockResponse(string $body, int $code = 200, array $headers = []): ResponseInterface {
        $response = self::$responseFactory->createResponse($code)
            ->withBody(self::$streamFactory->createStream($body));

        foreach ($headers as $name => $value) {
            $response = $response->withHeader($name, $value);
        }

        return $response;
    }

    const USER_ID = '00-0000000000000';
}