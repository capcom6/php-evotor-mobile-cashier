<?php

namespace SoftC\Evotor\MobileCashier\Tests;

use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\MessageFactory;
use Http\Mock\Client as MockClient;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use SoftC\Evotor\MobileCashier\Client;
use SoftC\Evotor\MobileCashier\Data\Position;
use SoftC\Evotor\MobileCashier\Data\Receipt;

final class ClientTest extends TestCase
{
    private MessageFactory $messageFactory;

    protected function setUp(): void
    {
        $this->messageFactory = MessageFactoryDiscovery::find();
    }

    public function testCreate(): void
    {
        $userId = '00-0000000000000';

        $uuid = 'uuid';
        $positions = [
            new Position('uuid', 'name', 'шт', 0, 123.45, 1),
        ];
        $should_print_receipt = false;

        $receipt = new Receipt($uuid, $positions, $should_print_receipt);

        $httpClient = new MockClient();

        $client = new Client($userId, $httpClient);
        $response = $client->create($receipt);

        $this->assertTrue($response);
        $this->assertCount(1, $httpClient->getRequests());

        $request = $httpClient->getLastRequest();
        $this->assertArrayHasKey('userId', $request->getHeaders());
        $this->assertContains($userId, $request->getHeader('userId'));
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
    private function mockResponse(string $body, int $code = 200, array $headers = ['Content-Type' => 'application/json']): ResponseInterface
    {
        return $this->messageFactory->createResponse($code, null, $headers, $body);
    }
}