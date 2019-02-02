<?php namespace Core\Http;

use Core\Auth\Authenticator;
use Core\Http\Response;

class HttpHandler implements HttpInterface{
    
    protected $entity;
    protected $router;

    /**
     * Set up a new HTTPEntity using incoming request information
     */
    public function __construct(HttpEntity $entity, HttpRouter $router) {
        $this->entity = $entity;
        $this->router = $router;

        $this->router->mapRoute($this->entity);
    }
    
    /**
     * Dispatch to appropriate class
     */
    public function dispatchRequest() {
        if(!$this->router->routeExists())
            return new Response([], 404, 'Not found');
        if(!$this->router->validateRoute())
            return new Response([], 400, 'Malformed request');
        return $this->checkPermissions();
    }

    public function checkPermissions() {
        if($this->router->getActiveRoute()->isProtected())
            return $this->authenticate();
        else
            return $this->router->dispatch();
    }

    /**
     * Authenticate protected route
     */

    public function authenticate() {
        $result = Authenticator::authFromToken($this->entity->getAuthorizationHeader());
        if($result->getStatus() != 200)
            return $result;
        
        return $this->router->dispatch();
    }

    /**
     * Send the response JSON formatted
     */

    public function sendJsonHTTPResponse($statusCode, $payload = [], $contentType = 'application/json') {
        header('Content-type: '. $contentType, false, $statusCode);
        echo json_encode($payload);
    }    
}