<?php
/**
 * DomainCartModel handles the operations related to the cart, including adding, removing,
 * retrieving, and calculating total values for domains in the cart.
 *
 * It interacts with the database to fetch, add, and remove cart items, and calculates
 * subtotal, tax, and total values based on the items in the cart.
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../db/pdo.php';

class DomainCartModel
{

    /**
     * Checks if a domain is already present in the cart.
     *
     * @param PDO $pdo The database connection object.
     * @param string $domain The domain name to check.
     * @param string $extension The domain extension to check.
     *
     * @return bool True if the domain is in the cart, false otherwise.
     */
    private function isDomainInCart($pdo, $domain, $extension)
    {
        $checkQuery = "SELECT COUNT(*) FROM cart WHERE domain = :domain AND extension = :extension";
        $checkStmt = $pdo->prepare($checkQuery);
        $checkStmt->bindParam(':domain', $domain);
        $checkStmt->bindParam(':extension', $extension);
        $checkStmt->execute();
        $count = $checkStmt->fetchColumn();

        if ($count > 0) {
            return true;
        }
        return false;
    }

    /**
     * Adds a domain to the cart if it's not already present and the status is not 'active'.
     *
     * @param string $domain The domain name to add.
     * @param string $extension The domain extension to add.
     * @param float $price The price of the domain.
     * @param string $currency The currency of the price.
     * @param string $status The status of the domain (must not be 'active').
     *
     * @return bool True if the domain was successfully added, false otherwise.
     */
    public function addToCart($domain, $extension, $price, $currency, $status)
    {
        if ($status === 'active') {
            return false;
        }

        try {
            $pdo = get_pdo();

            if ($this->isDomainInCart($pdo, $domain, $extension)) {
                return false;
            }

            $query = "INSERT INTO cart (domain, extension, price, currency) 
                  VALUES (:domain, :extension, :price, :currency)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':domain', $domain);
            $stmt->bindParam(':extension', $extension);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':currency', $currency);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Removes a domain from the cart.
     *
     * @param string $domain The domain name to remove.
     * @param string $extension The domain extension to remove.
     *
     * @return bool True if the domain was successfully removed, false otherwise.
     */
    public function removeFromCart($domain, $extension)
    {
        try {
            $pdo = get_pdo();
            $query = "DELETE FROM cart WHERE domain = :domain AND extension = :extension";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':domain', $domain);
            $stmt->bindParam(':extension', $extension);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            error_log("Error removing domain from cart: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Retrieves all items currently in the cart.
     *
     * @return array An array of cart items.
     */
    public function getCart()
    {
        try {
            $pdo = get_pdo();
            $query = "SELECT * FROM cart";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching cart: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Clears all items from the cart.
     *
     * @return bool True if the cart was successfully cleared, false otherwise.
     */
    public function clearCart()
    {
        try {
            $pdo = get_pdo();
            $query = "DELETE FROM cart";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            error_log("Error clearing cart: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Calculates the subtotal of the items in the cart.
     *
     * @return float The subtotal of the cart items.
     */
    public function getSubTotal()
    {
        try {
            $pdo = get_pdo();
            $query = "SELECT SUM(price) AS subtotal FROM cart";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return isset($result['subtotal']) ? $result['subtotal'] : 0;
        } catch (PDOException $e) {
            error_log("Error calculating subtotal: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Calculates the tax based on the subtotal.
     *
     * @return float The tax amount for the cart.
     */
    public function getTax()
    {
        $subTotal = $this->getSubTotal();
        $taxRate = VAT_RATE;
        return round($subTotal * $taxRate, 2);
    }

    /**
     * Calculates the total, including both subtotal and tax.
     *
     * @return float The total amount (subtotal plus tax).
     */
    public function getTotal()
    {
        return round($this->getSubTotal() + $this->getTax(), 2);
    }
}