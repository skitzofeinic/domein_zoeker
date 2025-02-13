<?php
/**
 * Routes order-related actions to appropriate controller methods based on the action parameter.
 *
 * This function processes the specified action and calls the relevant method from the
 * OrdersController class. It handles actions like displaying orders, placing an order,
 * and removing an order.
 *
 * @param string $action The action to be performed, such as 'place-order', 'remove-order', or empty for viewing orders.
 *
 * @return void
 */

require_once('src/controllers/ordersController.php');

function orderRoute($action)
{
    $controller = new OrdersController();

    switch (strtolower($action)) {
        case '':
            $controller->index();
            break;
        case 'place-order':
            $controller->placeOrder();
            break;
        case 'remove-order':
            $controller->removeOrder();
            break;
        default:
            echo '404 Not Found';
    }
}