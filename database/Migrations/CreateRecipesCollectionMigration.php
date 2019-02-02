<?php namespace Migrations;

use MongoDB\Client;
use Core\Util\Config;

class CreateRecipesCollectionMigration {
    public function run() {
        $database = Config::get('mongo_database'); 
        $hostname = Config::get('mongo_host'); 
        $port     = Config::get('mongo_port'); 
 
        $db = (new Client("mongodb://{$hostname}:{$port}"))->hellofreshtest;        
        $db->createCollection('recipes');
    }
}