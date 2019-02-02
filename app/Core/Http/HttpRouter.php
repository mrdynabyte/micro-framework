<?php namespace Core\Http;

use Core\Util\Config;
use Core\Util\YamlReader;

class HttpRouter implements HttpRouterInterface {
    protected $route;

    public function mapRoute(HttpEntity $entity) {
        $routes = YamlReader::parseFile(Config::get('routes'));
        $route  = array_filter($routes , function($route) use ($entity) {
            $pattern = preg_replace('/\{[a-zA-Z0-9]+\}/', '[\{?\/a-zA-z0-9\}?]+', str_replace('/', '\/', trim($route['url'])));
            $success = preg_match('/'.$pattern.'/', $entity->getRequestUri());
            return $entity->getMethod() == $route['method'] && (bool) $success;
        });

        $this->parseRoute($route);
        
        if($this->routeExists())
            $this->configRouteFromEntity($entity);

        return $this->route;
    }

    public function parseRoute($route) {
        
        if(empty($route))
            return $this->route = null;

        $alias      = array_keys($route);
        $specs      = array_shift($route);
        $controller = explode('@', $specs['controller']);
        $url        = $specs['url'];
        $protected  = isset($specs['protected']) ? true : false;
        
        $this->route = new Route(array_shift($alias), array_shift($controller), array_shift($controller), $url, $protected);
    }
    
    public function validateRoute() {
        if($this->route->getAlias() && $this->route->getAction() && $this->route->getHandlerClass() && $this->route->getUrl())
            return true;
        return false;
    }

    public function routeExists() {
        if($this->route == null)
            return false;
        return true;
    }

    public function configRouteFromEntity($httpEntity) {
        $this->route->matchParams($httpEntity->getRequestUri());
        $this->route->appendPayload($httpEntity->getPayload());
    }

    public function getActiveRoute() {
        return $this->route;
    }

    public function dispatch() {
        return $this->route->execute();
    }
}   