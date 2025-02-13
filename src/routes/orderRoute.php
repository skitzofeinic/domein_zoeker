<?php
require_once ('src/controllers/ordersController.php');

function orderRoute($action, $id){
    $controller = new OrdersController();

    switch (strtolower($action)) {
        case '':
            $controller->index();
            break;
        case 'place-order':
            $controller->placeOrder();
            break;
        case 'remove-order':
            $controller->removeOrder($id);
            break;
        default:
            echo '404 Not Found';
    }
}