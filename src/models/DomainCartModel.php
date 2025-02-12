<?php
require_once __DIR__ .'/../config/config.php';
require_once __DIR__ . '/../db/pdo.php';
class DomainCartModel {
    private function isDomainInCart($pdo, $domain, $extension) {
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

    public function addToCart($domain, $extension, $price, $currency, $status) {
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
    public function removeFromCart($domain, $extension) {
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
    public function getCart() {
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
    public function clearCart() {
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
    public function getSubTotal() {
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
    public function getTax() {
        $subTotal = $this->getSubTotal();
        $taxRate = VAT_RATE;
        return $subTotal * $taxRate;
    }
    public function getTotal() {
        return $this->getSubTotal() + $this->getTax();
    }
}