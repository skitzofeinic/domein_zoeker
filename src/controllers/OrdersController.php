<?php
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

    public function index()
    {
        $orders = $this->ordersModel->getAllOrders();
        $orderItems = [];

        foreach ($orders as $order) {
            $orderItems[$order['id']] = $this->ordersModel->getOrderItems($order['id']);
        }

        require __DIR__ . '/../views/orders.php';
    }


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