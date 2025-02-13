<?php
/**
 * OrdersController class.
 *
 * This class handles operations related to orders, including placing an order,
 * viewing all orders, and removing an order from the system.
 *
 */

require_once __DIR__ . '/../models/DomainOrderModel.php';
require_once __DIR__ . '/../models/DomainCartModel.php';

class OrdersController
{
    private $ordersModel;
    private $cartModel;

    public function __construct()
    {
        $this->ordersModel = new DomainOrderModel();
        $this->cartModel = new DomainCartModel();
    }

    /**
     * Display all orders.
     *
     * Retrieves all orders and their associated items, then includes the orders view.
     *
     * @return void
     */
    public function index()
    {
        $orders = $this->ordersModel->getAllOrders();
        $orderItems = [];

        foreach ($orders as $order) {
            $orderItems[$order['id']] = $this->ordersModel->getOrderItems($order['id']);
        }

        require __DIR__ . '/../views/orders.php';
    }

    /**
     * Place an order.
     *
     * Places an order by retrieving cart items, calculating subtotal, tax, and total,
     * creating the order, adding order items, and clearing the cart.
     * Responds with JSON indicating success or failure.
     *
     * @return void
     */
    public function placeOrder()
    {
        $cartItems = $this->cartModel->getCart();
        if (empty($cartItems)) {
            echo json_encode(['success' => false, 'message' => 'Cart is empty.']);
            return;
        }

        $subtotal = $this->cartModel->getSubTotal();
        $vat = $this->cartModel->getTax();
        $total = $this->cartModel->getTotal();

        $orderId = $this->ordersModel->createOrder($subtotal, $vat, $total);

        if (!$orderId) {
            echo json_encode(['success' => false, 'message' => 'Failed to create order.']);
            return;
        }

        foreach ($cartItems as $item) {
            $this->ordersModel->addOrderItem($orderId, $item['domain'], $item['extension'], $item['price'], $item['currency']);
        }

        $this->cartModel->clearCart();
        echo json_encode(['success' => true, 'message' => 'Order placed successfully!']);
    }


    /**
     * Remove an order.
     *
     * Removes an order based on the provided order ID. Expects a POST request with a JSON payload
     * containing the order ID to be removed.
     * Responds with a message indicating success or failure.
     *
     * @return void
     */
    public function removeOrder()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo "NOT POST REQUEST";
        }

        $jsonData = file_get_contents('php://input');
        $data = json_decode($jsonData, true);

        if (!isset($data['id'])) {
            echo "Invalid data received.";
        }

        $orderId = $data['id'];
        if (!$this->ordersModel->removeOrder($orderId)) {
            echo "Failed to remove domain from cart.";
        }

        echo "Domain removed from cart successfully!";
    }

}