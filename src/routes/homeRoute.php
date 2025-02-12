<?php
require_once ('src/controllers/searchController.php');

function homeRoute($action, $id){
    $controller = new SearchController();

    switch (strtolower($action)) {
        case '':
            $controller->index();
            break;
        case 'search':
            $controller->searchDomains();
            break;
        default:
            echo '404 Not Found';
    }
}