<?php
use core\Router;
use core\Database;

//Front controller

//General settings
ini_set('display_errors', 0);
error_reporting(E_ALL);
session_start();

//Connecting system files
spl_autoload_register(function($class)  {
    $path = str_replace('\\', '/', $class . '.php');
    if (file_exists($path)) {
        require $path;
    }
});

$db = new Database;
$router = new Router;
$router->run();