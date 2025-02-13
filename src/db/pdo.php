<?php
/**
 * Connects to the MySQL database using PDO.
 *
 * @return PDO|null Returns a PDO instance if successful, or null if the connection fails.
 */

require_once __DIR__ . '/../config/db.php';

function get_pdo()
{
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";

        $pdo = new PDO($dsn, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;

    } catch (PDOException $e) {
        error_log("connection failed: " . $e->getMessage());
    }
    return null;
}
