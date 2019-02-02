<?php

/**
 * Autoload application and core classes
 */
require __DIR__.'/bootstrap/autoload.php';
require __DIR__.'/bootstrap/bootstrap.php';

/**
 * Handle incoming request
 */

$app =  new Core\Application();
$app->handle();