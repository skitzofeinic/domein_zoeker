<?php

require_once __DIR__ . '/../models/DomainCartModel.php';


class CartController
{
    private $cartModel;

    public function __construct()
    {
        $this->cartModel = new DomainCartModel();
    }

    public function index()
    {
        $cart_items = $this->cartModel->getCart();
        $subtotal = $this->cartModel->getSubtotal();
        $tax = $this->cartModel->getTax();
        $total = $this->cartModel->getTotal();

        require __DIR__ . '/../views/cart.php';
    }

    public function addToCart()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $jsonData = file_get_contents('php://input');
            $data = json_decode($jsonData, true);

            if (!isset($data['domain'], $data['extension'], $data['price'], $data['currency'], $data['status'])) {
                echo "Invalid data received.";
                return;
            }

            $extension = $data['extension'];
            $price = $data['price'];
            $currency = $data['currency'];
            $status = $data['status'];
            $domain = $data['domain'];

            if (!$this->cartModel->addToCart($domain, $extension, $price, $currency, $status)) {
                echo "Failed to add domain to cart.";
                return;
            }

            echo "Domain added to cart successfully!";
        }
    }

    public function removeFromCart()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $jsonData = file_get_contents('php://input');
            $data = json_decode($jsonData, true);
            if (!isset($data['domain'], $data['extension'])) {
                echo "Invalid data received.";
            }
            $domain = $data['domain'];
            $extension = $data['extension'];

            if (!$this->cartModel->removeFromCart($domain, $extension)) {
                echo "Failed to remove domain from cart.";
            }

            echo "Domain removed from cart successfully!";
        } else {
            echo "Invalid data received.";
        }
    }


    public function clearCart()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);

            if (!isset($data['action']) || $data['action'] != 'clear_cart') {
                echo json_encode(['success' => false, 'message' => 'Invalid request.']);
            }

            $result = $this->cartModel->clearCart();

            if (!$result) {
                echo json_encode(['success' => false, 'message' => 'Failed to clear the cart.']);
            }
            echo json_encode(['success' => true, 'message' => 'Cart has been cleared!']);

        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
        }
    }
}
