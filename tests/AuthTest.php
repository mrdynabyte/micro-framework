<?php namespace tests;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class AuthTest extends TestCase {

    protected $client;

    public function setUp() {
        $this->client = new Client();
        $this->client->post('http://localhost/signup', ['json' =>['email' => 'john@doe.com', 'password' => 123456]]);
    }

    public function testAuthentication() {
        $result = $this->client->post('http://localhost/auth', ['json' => ['email' => 'john@doe.com', 'password' =>123456]]);
        $body = json_decode((string) $result->getBody(), true);
        $this->assertEquals($result->getStatusCode(), 200);
        $this->assertArrayHasKey('token', $body);
    }

    public function testMissingFields() {
        $message = '';
        $statusCode = null;

        try {
            $result = $this->client->post('http://localhost/auth', ['json' => ['email' => 'john@doe.com']]);
        } catch(ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $message    =  json_decode((string) $e->getResponse()->getBody());
        }        

        $this->assertEquals($statusCode, 400);
        $this->assertJsonStringEqualsJsonString(json_encode($message), json_encode(['error' => 'You must provide valid credentials']));
        
    }

    public function testInvalidCredentials() {
        $message = '';
        $statusCode = null;

        try {
            $result = $this->client->post('http://localhost/auth', ['json' => ['email' => 'john@doe.com', 'password' => 'somerandompassword']]);
        } catch(ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $message    =  json_decode((string) $e->getResponse()->getBody());
        }        

        $this->assertEquals($statusCode, 400);
        $this->assertJsonStringEqualsJsonString(json_encode($message), json_encode(['error' => 'Invalid email or password']));
    }

    public function tearDown() {
        $result = $this->client->post('http://localhost/auth/', ['json' =>['email' => 'john@doe.com', 'password' => 123456]]);
        $body = json_decode((string) $result->getBody(), true);        
        $result = $this->client->delete('http://localhost/user', ['headers' => ['Authorization' => 'Bearer '. $body['token']],
                                                               'json' => ['email' =>'john@doe.com']]);
    }    

}