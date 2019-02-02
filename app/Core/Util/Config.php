<?php namespace Core\Util;  

use Exception;

class Config {
    const FILENAME = 'app.php';
    const FILEPATH = 'config/';

    public static function get( $name ) {
        $contents = require realpath(__DIR__.'/../../../'.self::FILEPATH.self::FILENAME);
        if(!array_key_exists($name, $contents))
             throw new Exception('No reference found for '.$name);
        return $contents[$name];
    }
}