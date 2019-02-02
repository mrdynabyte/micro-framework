<?php namespace Core\Http;

interface RouteInterface {
    public function execute();
    public function getAction();
    public function getParams();
    public function getHandlerClass();
    public function getUrl();
    public function matchParams($params);
    public function setAction($action);
    public function setHandlerClass($class);
    public function setParams($fromRequestUri);
    public function setUrl($url);
}