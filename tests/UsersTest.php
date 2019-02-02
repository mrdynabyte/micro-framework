<?php namespace tests;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class UsersTest extends TestCase {

    protected $client;

    public function setUp() {
        $this->client = new Client();
    }

    public function testRegisterUser() {
        $result = $this->client->post('http://localhost/signup', ['json' =>['email' => 'john@doe.com', 'password' => 123456]]);
        $body = json_decode((string) $result->getBody(), true);

        $this->assertEquals($result->getStatusCode(), 200);
    }

    public function testDeleteUserByEmail() {
        $result = $this->client->post('http://localhost/auth/', ['json' =>['email' => 'john@doe.com', 'password' => 123456]]);
        $body = json_decode((string) $result->getBody(), true);

        $result = $this->client->delete('http://localhost/user', ['headers' => ['Authorization' => 'Bearer '. $body['token']],
                                                                  'json' => ['email' =>'john@doe.com']]);
        $body = json_decode((string) $result->getBody(), true);

        $this->assertEquals($result->getStatusCode(), 200);
        $this->assertJsonStringEqualsJsonString(json_encode($body), json_encode(['message' => 'OK']));
    }

}