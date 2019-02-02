<?php namespace Core;

use Core\Http\HttpHandler;
use Core\Http\HttpEntity;
use Core\Http\HttpRouter;
use Core\Util\YamlReader;
use Core\Util\Config;

class Application extends Kernel {
    public function __construct() {
        parent::__construct(new HttpHandler(new HttpEntity(), new HttpRouter()));
    }
}