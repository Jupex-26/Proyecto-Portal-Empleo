<?php
require __DIR__ . '/../bootstrap.php'; 

// Carga las clases
require PROJECT_ROOT . 'vendor/autoload.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
use app\Router;

$router=new Router();
$router->route();

?>