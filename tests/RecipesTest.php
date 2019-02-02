<?php namespace tests;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class RecipesTest extends TestCase {

    protected $client;
    protected $token;
    protected $recipe;

    public function setUp() {
        $this->client = new Client();

        $this->client->post('http://localhost/signup', ['json' =>['email' => 'john@doe.com', 'password' => 123456]]);
        
        $result = $this->client->post('http://localhost/auth/', ['json' =>['email' => 'john@doe.com', 'password' => 123456]]);
        
        $this->token = json_decode((string) $result->getBody(), true)['token'];

        $this->recipe = ['name' => 'Ramen', 'prep_time' => 30, 'difficulty' => 3, 'vegetarian' => false];
    }

    public function testCreateRecipe() {

        $result = $this->client->post('http://localhost/recipes', ['headers' => ['Authorization' => 'Bearer '. $this->token] ,
                                                                   'json' => $this->recipe]);
        $body = json_decode((string) $result->getBody(), true);
        $id = $body['id'];
        unset($body['id']);

        $this->assertEquals($result->getStatusCode(), 200);
        $this->assertJsonStringEqualsJsonString(json_encode($body), json_encode($this->recipe));
    
        return $id;
    }

    /**
     * @depends testCreateRecipe
     */

    public function testGetRecipe($id) {
        $this->recipe['id'] = $id;

        $result = $this->client->get('http://localhost/recipes/' . $id, ['headers' => ['Authorization' => 'Bearer '. $this->token]]);
        $body = json_decode((string) $result->getBody(), true);

        $this->assertEquals($result->getStatusCode(), 200);
        $this->assertJsonStringEqualsJsonString(json_encode($body), json_encode($this->recipe));
    }
    
    /**
     * @depends testCreateRecipe
     */

    public function testUpdateRecipe($id) {
        $this->recipe = [
            'id' => $id,
            'name' => 'Chimichangas',
            'prep_time' => 40,
            'difficulty' => 1,
            'vegetarian' => false
        ];

        $result = $this->client->put('http://localhost/recipes/' . $id, ['headers' => ['Authorization' => 'Bearer '. $this->token], 'json' => $this->recipe]);
        $body = json_decode((string) $result->getBody(), true);

        $this->assertEquals($result->getStatusCode(), 200);
        $this->assertTrue($body['updated']);
        $this->assertJsonStringEqualsJsonString(json_encode($body['recipe']), json_encode($this->recipe));    
    }


    /**
     * @depends testCreateRecipe
     */   
    public function testListRecipes() {
        $result = $this->client->get('http://localhost/recipes');
        $body = json_decode((string) $result->getBody(), true);

        $result = array_filter($body, function($item) { 
                                        return $item['name'] == 'Chimichangas';
                                      });
        $recipe = array_shift($result);

        $this->assertArrayHasKey('name', $recipe);
        $this->assertArrayHasKey('difficulty', $recipe);
        $this->assertArrayHasKey('prep_time', $recipe);
        $this->assertArrayHasKey('vegetarian', $recipe);
    }

    /**
     * @depends testCreateRecipe
     */     

    public function testSearchRecipe() {
        $result = $this->client->post('http://localhost/recipes/search', ['json' =>['criteria' => ['name' => 'Chimichangas']]]);
        $body = json_decode((string) $result->getBody(), true);
        $result = array_filter($body, function($item) { return $item['name'] == 'Chimichangas';});
        $recipe = array_shift($result);
        
        $this->assertArrayHasKey('name', $recipe);
        $this->assertArrayHasKey('difficulty', $recipe);
        $this->assertArrayHasKey('prep_time', $recipe);
        $this->assertArrayHasKey('vegetarian', $recipe);
    }
        

    /**
     * @depends testCreateRecipe
     */

    public function testDeleteRecipe($id) {
        
        $result = $this->client->delete('http://localhost/recipes/' . $id, ['headers' => ['Authorization' => 'Bearer '. $this->token]]);        
        $body = json_decode((string) $result->getBody(), true);
        
        $this->assertEquals($result->getStatusCode(), 200);
        $this->assertTrue($body['deleted']);
    }

    /**
     * @depends testCreateRecipe
     */    

    public function testRateRecipe($id) {
        $result = $this->client->post('http://localhost/recipes/' . $id . '/rating', ['headers' => ['Authorization' => 'Bearer '. $this->token], 'json' => ['rating' => 3]]);        
        $body = json_decode((string) $result->getBody(), true);
    
        unset($body['id']);

        $this->assertEquals($result->getStatusCode(), 200);    
        $this->assertJsonStringEqualsJsonString(json_encode($body), json_encode(['recipe_id' => $id, 'rating' => 3]));    
    }

    public function tearDown() {
        $this->client->delete('http://localhost/user', ['headers' => ['Authorization' => 'Bearer '. $this->token], 
                                                                   'json' => ['email' =>'john@doe.com']]);
    }        
}