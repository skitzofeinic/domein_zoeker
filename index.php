<?php
session_start();

require_once('src/routes/homeRoute.php');
require_once('src/routes/cartRoute.php');
require_once('src/routes/orderRoute.php');


$basePath = 'domein_zoeker';
$requestUri = ltrim($_SERVER['REQUEST_URI'], '/');

if (strpos($requestUri, $basePath) === 0) {
    $requestUri = substr($requestUri, strlen($basePath));
    $requestUri = ltrim($requestUri, '/');
}

$parts = explode('/', $requestUri);
$path = isset($parts[0]) ? $parts[0] : '';
$action = isset($parts[1]) ? $parts[1] : '';
$id = isset($parts[2]) ? $parts[2] : null;


switch (strtolower($path)) {
    case '':
        header('Location: home');
        break;
    case 'home':
        homeRoute($action);
        break;
    case 'cart':
        cartRoute($action);
        break;
    case 'order':
        orderRoute($action);
        break;
    default:
        echo '404 Not Found';
}
