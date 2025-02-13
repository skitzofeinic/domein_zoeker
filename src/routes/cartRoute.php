<?php
/**
 * Routes cart actions to appropriate controller methods based on the action parameter.
 *
 * This function processes the specified action and calls the relevant method from the
 * CartController class. It handles actions for displaying the cart, adding an item to the cart,
 * removing an item from the cart, and clearing the entire cart.
 *
 * @param string $action The action to be performed, such as 'add', 'remove', 'clear', or empty for viewing the cart.
 *
 * @return void
 */

require_once('src/controllers/CartController.php');

function cartRoute($action)
{
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