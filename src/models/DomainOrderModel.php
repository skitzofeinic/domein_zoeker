<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../db/pdo.php';

class DomainOrderModel
{
    public function createOrder($subtotal, $vat, $total)
    {
        $pdo = get_pdo();
        $query = "INSERT INTO orders (subtotal, vat, total, created_at) 
                  VALUES (:subtotal, :vat, :total, NOW())";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':subtotal', $subtotal);
        $stmt->bindParam(':vat', $vat);
        $stmt->bindParam(':total', $total);
        $stmt->execute();
        return $pdo->lastInsertId();
    }

    public function addOrderItem($orderId, $domain, $extension, $price, $currency)
    {
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
    }

    public function getAllOrders() {
        $pdo = get_pdo();
        $query = "SELECT * FROM orders";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getOrderItems($orderId) {
        $pdo = get_pdo();
        $query = "SELECT * FROM order_items WHERE order_id = :orderId";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':orderId', $orderId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getOrder($orderId) {
        $pdo = get_pdo();
        $query = "SELECT * FROM orders WHERE id = :orderId";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':orderId', $orderId);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function removeOrder($orderId)
    {
        $pdo = get_pdo();
        $query = "DELETE FROM orders WHERE id = :orderId";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':orderId', $orderId);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }

}