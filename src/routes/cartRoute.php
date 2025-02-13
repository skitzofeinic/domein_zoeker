<?php
require_once ('src/controllers/CartController.php');

function cartRoute($action, $id) {
    $controller = new CartController();

    switch (strtolower($action)) {
        case '':
            $controller->index();
            break;
        case 'add':
            $controller->addToCart();
            break;
        case 'remove':
            $controller->removeFromCart();
            break;
        case 'clear':
            $controller->clearCart();
            break;
        default:
            echo '404 Not Found';
    }
}