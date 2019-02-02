<?php namespace Core\Http;

interface HttpEntityInterface {

    public function setRequestUri($requestUri);
    public function setPayload($payload);
    public function setMethod($method);
    public function setContentType($contentType);

    public function getRequestUri();
    public function getPayload();
    public function getMethod();
    public function getContentType();

    public function readPayload();
}