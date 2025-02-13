<?php
/**
 * Routes home page actions to appropriate controller methods based on the action parameter.
 *
 * This function processes the specified action and calls the relevant method from the
 * SearchController class. It handles actions for displaying the home page (default) and
 * performing a domain search.
 *
 * @param string $action The action to be performed, such as 'search' or empty for viewing the home page.
 * @param int $id The ID parameter (not used in this function, but can be used for future extensions).
 *
 * @return void
 */

require_once('src/controllers/searchController.php');

function homeRoute($action)
{
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