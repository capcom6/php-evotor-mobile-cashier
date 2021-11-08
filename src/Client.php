<?php

namespace SoftC\Evotor\MobileCashier;

/**
 * Description of Client
 *
 * @author capcom
 */
class Client {
    const BASE_URI = 'https://qrstats.ru:8088';

    protected $userId;
    
    protected $client;


    public function __construct(string $userId) {
        $this->userId = $userId;
        
        $this->client = new \GuzzleHttp\Client([
            // Base URI is used with relative requests
            'base_uri' => self::BASE_URI,
            \GuzzleHttp\RequestOptions::SYNCHRONOUS => true,
            \GuzzleHttp\RequestOptions::TIMEOUT => 30
        ]);
    }
    
    public function create(Data\Receipt $receipt) {
        $response = $this->client->post('/api/v1/receipt/pre/create', [
            \GuzzleHttp\RequestOptions::HEADERS  => [
                'userId' => $this->userId,
                'Content-Type' => 'application/json'
            ],
            \GuzzleHttp\RequestOptions::BODY => json_encode($receipt)
        ]);
        
        if ($response->getStatusCode() !== 200) {
            throw new \Exception($response->getReasonPhrase(), $response->getStatusCode());
        }
        
        return json_decode($response->getBody()->getContents());
    }
}
