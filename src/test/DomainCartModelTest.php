<?php
require_once('../models/DomainCartModel.php');

session_start();

$cartModel = new DomainCartModel();

// Test: Add 3 domains to cart
$addResult1 = $cartModel->addToCart('example1', 'com', 10.99, 'USD', 'inactive');
echo "Add 'example1.com' to cart result: " . ($addResult1 ? 'Success' : 'Failed') . "\n";

$addResult2 = $cartModel->addToCart('example2', 'net', 12.50, 'USD', 'inactive');
echo "Add 'example2.net' to cart result: " . ($addResult2 ? 'Success' : 'Failed') . "\n";

$addResult3 = $cartModel->addToCart('example3', 'org', 15.75, 'USD', 'inactive');
echo "Add 'example3.org' to cart result: " . ($addResult3 ? 'Success' : 'Failed') . "\n";

// Test: Retrieve cart
$cart = $cartModel->getCart();
echo "Cart after adding 3 domains: \n";
print_r($cart);

// Test: Subtotal, VAT, and Total calculations
$subtotal = $cartModel->getSubTotal();
echo "Subtotal: $" . $subtotal . "\n";

$vat = $cartModel->getTax();
echo "VAT (21%): $" . $vat . "\n";

$total = $cartModel->getTotal();
echo "Total (Subtotal + VAT): $" . $total . "\n";

// Test: Try adding duplicate domain
$addResult4 = $cartModel->addToCart('example1', 'com', 10.99, 'USD', 'inactive');
echo "Add duplicate 'example1.com' result: " . ($addResult4 ? 'Success' : 'Failed') . "\n";

// Test: Remove one domain from cart
$removeResult = $cartModel->removeFromCart('example2', 'net');
echo "Remove 'example2.net' from cart result: " . ($removeResult ? 'Success' : 'Failed') . "\n";

// Test: Retrieve cart after removal
$cartAfterRemoval = $cartModel->getCart();
echo "Cart after removing 'example2.net': \n";
print_r($cartAfterRemoval);

// Test: Clear the cart
$cartModel->clearCart();
echo "Cart after clearing: \n";
$cartAfterClear = $cartModel->getCart();
print_r($cartAfterClear);
