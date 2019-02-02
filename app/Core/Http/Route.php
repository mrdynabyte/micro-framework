<?php namespace Core\Http;  

use Exception;
use Core\Http\RouteInterface;

class Route implements RouteInterface {

    private $alias;
    private $handlerClass;
    private $action;
    private $params;
    private $url;
    private $protected;

    public function __construct($alias, $handlerClass, $action, $url, $protected, $params = []) {
        $this->alias = $alias;
        $this->handlerClass = $handlerClass;
        $this->action = $action;
        $this->url = $url;
        $this->params = $params;
        $this->protected = $protected;
    }

 
    public function getAction() {
        return $this->action;
    }

    public function getHandlerClass() {
        return $this->handlerClass;
    }

    public function getParams() {
        return $this->params;
    }

    public function getUrl() {
        return $this->url;
    }

    public function getAlias() {
        return $this->alias;
    }

    public function appendPayload($payload) {
        $this->params['input'] = $payload;
    }

    public function setAction($action) {
        $this->action = $action;
    }

    public function setHandlerClass($class) {
        $this->handlerClass =  $class;
    }

    public function setParams($params) {
        $this->params = $params;
    }

    public function setUrl($url) {
        $this->url = $url;
    }

    public function setAlias($alias) {
        $this->alias = $alias;
    }

    public function isProtected() {
        return $this->protected;
    }

    public function matchParams($fromRequestUri) {
        $values = $this->getRequestIdValues($fromRequestUri);

        $ids = $this->trimBrackets($this->getUrlIds());

        if(count($ids) != count($values)) {
            throw new Exception('Missing parameters calling '. $fromRequestUri);
        } else {
            $this->params = array_combine($ids, $values);
        }
    }
    
    public function execute() {
        $class = new $this->handlerClass;
        $action = $this->action;
        return (empty($this->params)) ? $class->$action() : $class->$action($this->params);
    }
    
    private function getRequestIdValues($requestUri) {
        $urlTokens = explode('/', $this->url);
        $reqTokens = explode('/', $requestUri);

        return array_diff($reqTokens, $urlTokens);
    }

    private function getUrlIds() {
        return array_filter(explode('/', $this->url), function ($item) {
            return preg_match('/\{[a-zA-Z0-9]+\}/', $item);
        });
    }

    private function trimBrackets($ids) {
        return array_map(function($item) { 
            return trim($item, '{}');
        }, $ids);
    }

}