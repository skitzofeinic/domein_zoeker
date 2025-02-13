<?php
/**
 * Handles order and order item operations, including creating, fetching, and removing orders
 * and order items from the database.
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../db/pdo.php';

class DomainOrderModel
{
    /**
     * Creates a new order in the database.
     *
     * @param float $subtotal The subtotal amount of the order.
     * @param float $vat The VAT amount for the order.
     * @param float $total The total amount for the order.
     *
     * @return false|string The ID of the newly created order on success, or an error message on failure.
     */
    public function createOrder($subtotal, $vat, $total)
    {
        try {
            $pdo = get_pdo();
            $query = "INSERT INTO orders (subtotal, vat, total, created_at) 
                  VALUES (:subtotal, :vat, :total, NOW())";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':subtotal', $subtotal);
            $stmt->bindParam(':vat', $vat);
            $stmt->bindParam(':total', $total);
            $stmt->execute();
            return $pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error creating order" . $e->getMessage());
            return "Error creating order";
        }
    }

    /**
     * Adds an item to the order in the database.
     *
     * @param int $orderId The ID of the order.
     * @param string $domain The domain name for the order item.
     * @param string $extension The domain extension (e.g., ".com").
     * @param float $price The price of the domain.
     * @param string $currency The currency of the price (e.g., "USD").
     *
     * @return bool|string Returns true if the item is added successfully, or an error message on failure.
     */
    public function addOrderItem($orderId, $domain, $extension, $price, $currency)
    {
        try {
            $pdo = get_pdo();
            $query = "INSERT INTO order_items (order_id, domain, extension, price, currency) 
                  VALUES (:order_id, :domain, :extension, :price, :currency)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':order_id', $orderId);
            $stmt->bindParam(':domain', $domain);
            $stmt->bindParam(':extension', $extension);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':currency', $currency);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error creating order item" . $e->getMessage());
            return "Error creating order item";
        }
    }

    /**
     * Retrieves all orders from the database.
     *
     * @return array|string An array of orders on success, or an error message on failure.
     */
    public function getAllOrders()
    {
        try {
            $pdo = get_pdo();
            $query = "SELECT * FROM orders";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error fetching orders" . $e->getMessage());
            return "Error fetching orders";
        }
    }


    /**
     * Retrieves all items associated with a specific order.
     *
     * @param int $orderId The ID of the order.
     *
     * @return array|string An array of order items on success, or an error message on failure.
     */
    public function getOrderItems($orderId)
    {
        try {
            $pdo = get_pdo();
            $query = "SELECT * FROM order_items WHERE order_id = :orderId";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':orderId', $orderId);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error fetching order items" . $e->getMessage());
            return "Error fetching order items";
        }
    }

    /**
     * Retrieves a specific order by its ID.
     *
     * @param int $orderId The ID of the order.
     *
     * @return array|string The order details on success, or an error message on failure.
     */
    public function getOrder($orderId)
    {
        try {
            $pdo = get_pdo();
            $query = "SELECT * FROM orders WHERE id = :orderId";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':orderId', $orderId);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error fetching order items" . $e->getMessage());
            return "Error fetching order items";
        }
    }

    /**
     * Removes an order from the database by its ID.
     *
     * @param int $orderId The ID of the order to remove.
     *
     * @return bool|string Returns true if the order is successfully removed, or an error message on failure.
     */
    public function removeOrder($orderId)
    {
        try {
            $pdo = get_pdo();
            $query = "DELETE FROM orders WHERE id = :orderId";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':orderId', $orderId);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error removing order" . $e->getMessage());
            return "Error removing order";
        }
    }
}