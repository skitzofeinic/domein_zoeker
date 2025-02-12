<?php
session_start();

require_once ('src/routes/homeRoute.php');
require_once ('src/routes/cartRoute.php');


$basePath = 'domein_zoeker'; // Set your root path
$requestUri = ltrim($_SERVER['REQUEST_URI'], '/');

if (strpos($requestUri, $basePath) === 0) {
    $requestUri = substr($requestUri, strlen($basePath)); // Remove "domein_zoeker/"
    $requestUri = ltrim($requestUri, '/'); // Remove any extra leading slashes
}

$parts = explode('/', $requestUri);
$path = isset($parts[0]) ? $parts[0] : '';
$action = isset($parts[1]) ? $parts[1] : '';
$id = isset($parts[2]) ? $parts[2] : null;


switch (strtolower($path)) {
    case '':
        header('Location: home');
        exit();
        break;
    case 'home':
        homeRoute($action, $id);
        break;
    case 'cart':
        cartRoute($action, $id);
        break;
    default:
        echo '404 Not Found';
}
