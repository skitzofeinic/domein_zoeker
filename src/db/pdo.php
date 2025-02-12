<?php
require_once('../config/db.php');

try {
    $username = DB_USER;
    $password = DB_PASS;
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME.";charset=utf8mb4";;

    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $pdo;
} catch (PDOException $e) {
    echo ("connection failed: " . $e->getMessage());
}
