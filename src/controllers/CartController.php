<?php
/**
 * CartController class.
 *
 * This class handles cart operations such as displaying the cart,
 * adding items to the cart, removing items, and clearing the cart.
 *
 */

require_once __DIR__ . '/../models/DomainCartModel.php';

class CartController
{
    private $cartModel;

    public function __construct()
    {
        $this->cartModel = new DomainCartModel();
    }

    /**
     * Display the cart.
     *
     * Retrieves cart items, subtotal, tax, and total from the cart model,
     * and then includes the cart view.
     *
     * @return void
     */
    public function index()
    {
        $cart_items = $this->cartModel->getCart();
        $subtotal = $this->cartModel->getSubtotal();
        $tax = $this->cartModel->getTax();
        $total = $this->cartModel->getTotal();

        require __DIR__ . '/../views/cart.php';
    }

    /**
     * Add a domain to the cart.
     *
     * Expects a POST request with a JSON payload containing:
     * - domain: string Domain name.
     * - extension: string Domain extension.
     * - price: float Price of the domain.
     * - currency: string Currency (e.g., EUR).
     * - status: string Domain status ("free" or "active").
     *
     * @return void Echoes a success or failure message.
     */
    public function addToCart()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            return;
        }

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

    /**
     * Remove a domain from the cart.
     *
     * Expects a POST request with a JSON payload containing:
     * - domain: string Domain name to remove.
     * - extension: string Domain extension to remove.
     *
     * @return void Echoes a success or failure message.
     */
    public function removeFromCart()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo "Invalid data received.";
        }

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
    }

    /**
     * Clear the entire cart.
     *
     * Expects a POST request with a JSON payload containing:
     * - action: string Must be 'clear_cart' to trigger the cart clear.
     *
     * @return void Echoes a JSON-encoded response indicating success or failure.
     */
    public function clearCart()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
        }

        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['action']) || $data['action'] != 'clear_cart') {
            echo json_encode(['success' => false, 'message' => 'Invalid request.']);
        }

        $result = $this->cartModel->clearCart();
        if (!$result) {
            echo json_encode(['success' => false, 'message' => 'Failed to clear the cart.']);
        }

        echo json_encode(['success' => true, 'message' => 'Cart has been cleared!']);
    }
}
