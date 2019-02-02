<?php namespace tests;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class CoreTest extends TestCase {

    protected $client;

    public function setUp() {
        $this->client = new Client();
    }

    public function testRouteDispatched() {
        $res = $this->client->post('http://localhost/helloworld');
        $body = json_decode((string) $res->getBody(), true);
        
        $this->assertJsonStringEqualsJsonString(json_encode($body), json_encode(['message' => 'Hello world!']));
    }

    public function testBadRequest() {
        $message = '';
        $statusCode = null;
        try {
            $res = $this->client->post('http://localhost/whatever');
        } catch(ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $message    =  json_decode((string) $e->getResponse()->getBody());
        }

        $this->assertEquals($statusCode, 404);
        $this->assertJsonStringEqualsJsonString(json_encode($message), json_encode(['error' => 'Not found']));
        
    }
}