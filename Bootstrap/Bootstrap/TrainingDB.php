<?php
$host = 'localhost';
$dbname = 'Training';
$username = 'root';
$password = '';

try {
    // Connect to MySQL server first (no DB yet)
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");

    // Connect to the new database
    $pdo->exec("USE `$dbname`");

    // Create products table if it doesn't exist
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS products (
            product_id INT PRIMARY KEY AUTO_INCREMENT,
            product_name VARCHAR(255) NOT NULL,
            product_price DECIMAL(10,2) NOT NULL,
            product_img VARCHAR(255),  -- store image filename
            stock INT NOT NULL DEFAULT 0
        )
    ");

} catch (PDOException $e) {
    die("Database setup failed: " . $e->getMessage());
}
