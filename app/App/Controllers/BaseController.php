<?php namespace App\Controllers;

use Core\Util\Config;
use MongoDB\Client;

class BaseController {
    protected $client;

    public function __construct() {
        $hostname = Config::get('mongo_host');
        $port = Config::get('mongo_port');
        $this->client = (new Client("mongodb://{$hostname}:{$port}"))->hellofreshtest;
    }
}