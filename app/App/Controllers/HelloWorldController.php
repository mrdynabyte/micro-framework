<?php namespace App\Controllers;

use Core\Http\Response;

class HelloWorldController { 
    public function helloWorld() {
        return new Response("Hello world!");
    }
}