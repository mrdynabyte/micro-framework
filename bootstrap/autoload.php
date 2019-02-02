<?php
spl_autoload_register( function($class_name) {
    $base_dir  =  __DIR__.'/../';
    $path = str_replace('\\', '/', $class_name);

    $file = $base_dir . $path . '.php';
    
    if(file_exists($file)) {
        include $file;
    }
});

spl_autoload_register( function($class_name) {
    $base_dir  =  __DIR__.'/../database/';
    $path = str_replace('\\', '/', $class_name);

    $file = $base_dir . $path . '.php';
    if(file_exists($file)) {
        include $file;
    }
});

require realpath(__DIR__.'/../../vendor/autoload.php');