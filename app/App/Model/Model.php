<?php namespace App\Model; 
 
use MongoDB\Client; 
use Core\Util\Config; 
 
class Model { 
 
    protected $client; 
 
    protected $attributes; 
 
    public function __construct($collection) { 
        $database = Config::get('mongo_database'); 
        $hostname = Config::get('mongo_host'); 
        $port     = Config::get('mongo_port'); 
 
        $this->client = (new Client("mongodb://{$hostname}:{$port}"))->$database->$collection;         
    } 
 
    public function setAttributes($attributes) { 
        $this->attributes = $attributes; 
    }     
 
    public function getAttributes() { 
        return (array) $this->attributes; 
    } 
}