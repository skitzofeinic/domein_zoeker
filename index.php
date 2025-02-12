<?php
require_once ('src/routes/homeRoute.php');

$parts = explode('/', $url = ltrim($_SERVER['REQUEST_URI'], '/'));
$path = isset($parts[0]) ? $parts[0] : '';
$action = isset($parts[1]) ? $parts[1] : '';
$id = isset($parts[2]) ? $parts[2] : null;

switch (strtolower($path)) {
    case 'domein_zoeker':
        homeRoute($action, $id);
        break;
    default:
        echo '404 Not Found';
}
