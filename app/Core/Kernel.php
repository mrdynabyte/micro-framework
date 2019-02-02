<?php namespace Core;

use Core\Http\HttpInterface;

class Kernel {

    protected $manager;

    public function __construct(HttpInterface $manager) {
        $this->manager = $manager;    
    }

    public function handle() {
        $response = $this->manager->dispatchRequest();
        return $this->sendJsonResponse($response->getStatus(), $response->getPayload());
    }

    public function sendJsonResponse($statusCode = 200, $payload = array()) {
        return $this->manager->sendJsonHTTPResponse($statusCode, $payload);
    }
}