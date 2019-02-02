<?php namespace Core\Http;

class HttpEntity implements HttpEntityInterface{

    protected $requestUri;
    protected $payload;
    protected $method;
    protected $contentType;
    protected $authorization;

    public function __construct() {
        $this->authorization = (isset($_SERVER['HTTP_AUTHORIZATION'])) ? $_SERVER['HTTP_AUTHORIZATION'] : '' ;
        $this->contentType   = ($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : 'application/json' ;
        $this->method        = ($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET' ;
        $this->requestUri    =  $_SERVER['REQUEST_URI'];
        $this->readPayload();        
    }

    public function setContentType($contentType){
        $this->contentType = $contentType;
    }

    public function setMethod($method){
        $this->method = $method;
    }

    public function setPayload( $payload ){
        $this->payload = $payload;
    }

    public function setRequestUri( $requestUri ){
        $this->requestUri = $requestUri;
    }

    public function getContentType() {
        return $this->contentType;
    }

    public function getMethod() {
        return $this->method;
    }

    public function getPayload() {
        return $this->payload;
    }

    public function getRequestUri() {
        return $this->requestUri;
    }

    public function getAuthorizationHeader() {
        return $this->authorization;
    }

    public function readPayload() {
        if(!empty($_POST)) {
            $this->entity->setPayload($_POST);
        } else if(!empty($_GET)) {
            $this->setPayload($_GET);
        } else {
            $raw_data = file_get_contents('php://input');
            $this->setPayload(json_decode($raw_data));
        }
    }
    
}