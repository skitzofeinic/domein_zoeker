<?php

require_once __DIR__ . '/../models/DomainCartModel.php';


class CartController {
    private $cartModel;

    public function __construct() {
        $this->cartModel = new DomainCartModel(); // Assuming DomainCartModel is the class where cart actions are defined
    }

    public function index() {
        require __DIR__ . '/../views/cart.php';
    }

    public function addToCart() {
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




    public function removeFromCart() {
        if (isset($_GET['domain']) && isset($_GET['extension'])) {
            $domain = $_GET['domain'];
            $extension = $_GET['extension'];

            // Remove from cart
            if ($this->cartModel->removeFromCart($domain, $extension)) {
                echo "Domain removed from cart successfully!";
            } else {
                echo "Failed to remove domain from cart.";
            }
        } else {
            echo "Domain and extension must be provided.";
        }
    }

    public function getCart() {
        $cart = $this->cartModel->getCart();
        if (!empty($cart)) {
            echo "Cart contents: ";
            print_r($cart);
        } else {
            echo "Your cart is empty!";
        }
    }

    public function clearCart() {
        $result = $this->cartModel->clearCart();
        if ($result) {
            echo "Cart has been cleared!";
        } else {
            echo "Failed to clear cart.";
        }
    }

    public function getSubTotal() {
        $subtotal = $this->cartModel->getSubTotal();
        echo "Subtotal: " . $subtotal;
    }

    public function getTax() {
        $tax = $this->cartModel->getTax();
        echo "Tax: " . $tax;
    }

    public function getTotal() {
        $total = $this->cartModel->getTotal();
        echo "Total: " . $total;
    }
}
