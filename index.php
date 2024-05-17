<?php
require_once (dirname(__FILE__) . "/Utils/Router.php");
require_once ("vendor/autoload.php");


$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$router = new Router();
$router->addRoute('/', function () {
    require __DIR__ . '/pages/index2.php';
});
$router->addRoute('/About', function () {
    require __DIR__ . '/pages/About.php';
});
$router->addRoute('/classroom', function () {
    require __DIR__ . '/pages/classroom.php';
});
$router->addRoute('/admin', function () {
    require __DIR__ . '/pages/admin.php';
});

$router->dispatch();