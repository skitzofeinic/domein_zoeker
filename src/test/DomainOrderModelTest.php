<?php
require_once('../models/DomainOrderModel.php');
require_once('../db/pdo.php');

// Start the session if needed (depends on your project setup)
session_start();

// Create a new instance of DomainOrderModel
$ordersModel = new DomainOrderModel();

// Test: Create an order
$subtotal = 30.00;
$vat = 6.30;  // 21% VAT
$total = 36.30;
$orderId = $ordersModel->createOrder($subtotal, $vat, $total);
echo "Order created with ID: " . $orderId . "\n";

// Test: Add items to the created order
$addItemResult1 = $ordersModel->addOrderItem($orderId, 'example1', 'com', 10.99, 'EUR');
echo "Add 'example1.com' to order result: " . ($addItemResult1 ? 'Success' : 'Failed') . "\n";

$addItemResult2 = $ordersModel->addOrderItem($orderId, 'example2', 'net', 12.50, 'EUR');
echo "Add 'example2.net' to order result: " . ($addItemResult2 ? 'Success' : 'Failed') . "\n";

$addItemResult3 = $ordersModel->addOrderItem($orderId, 'example3', 'org', 15.75, 'EUR');
echo "Add 'example3.org' to order result: " . ($addItemResult3 ? 'Success' : 'Failed') . "\n";

// Test: Retrieve all orders with their items
$ordersWithItems = $ordersModel->getAllOrdersWithItems();
echo "All orders with their items: \n";
print_r($ordersWithItems);

// Test: Retrieve a specific order by order ID
$orderDetails = $ordersModel->getOrder($orderId);
echo "Details of order ID " . $orderId . ": \n";
print_r($orderDetails);

// Test: Remove an order
$removeResult = $ordersModel->removeOrder($orderId);
echo "Remove order result: " . ($removeResult ? 'Success' : 'Failed') . "\n";

// Test: Try to retrieve the removed order (should return nothing)
$removedOrderDetails = $ordersModel->getOrder($orderId);
echo "Details of removed order ID " . $orderId . ": \n";
print_r($removedOrderDetails);
