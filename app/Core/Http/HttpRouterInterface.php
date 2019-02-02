<?php namespace Core\Http;

interface HttpRouterInterface {
    public function parseRoute($route);
    public function mapRoute(HttpEntity $entity);
}