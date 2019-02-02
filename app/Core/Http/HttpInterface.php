<?php namespace Core\Http;

interface HttpInterface  {
    public function dispatchRequest();
    public function sendJsonHTTPResponse($statusCode, $payload, $contentType);
    public function authenticate();
}