<?php
// IndexDB.php - Automatic Database & Table Creation
$host     = 'localhost';
$username = 'root';
$password = ''; // Change to 'root' if using MAMP/Docker
$dbname   = 'apple';

try {
    // 1. Connect to MySQL Server (no DB selected yet)
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 2. Create Database
    // Note: We use backticks around the variable to safely escape the identifier
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    // 3. Connect to the specific Database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 4. Create Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `products` (
            `product_id`     INT AUTO_INCREMENT PRIMARY KEY,
            `product_name`   VARCHAR(255) NOT NULL,
            `product_price`  DECIMAL(12,2) NOT NULL,
            `product_img`    VARCHAR(500) NOT NULL,
            `product_stocks` INT DEFAULT 0 NOT NULL,
            `created_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");

    // 5. Insert Data (Only if empty)
    if ($pdo->query("SELECT COUNT(*) FROM `products`")->fetchColumn() == 0) {
        $pdo->exec("
            INSERT INTO `products` (`product_name`, `product_price`, `product_img`, `product_stocks`) VALUES
            ('iPhone 17 Pro Max', 108990.00, 'Product1.png', 50),
            ('iPhone 16 Pro',     94990.00, 'Product2.png',     75),
            ('iPhone 13',         32619.00, 'Product3.png',       100)
        "); // <--- FIXED: Quote closed correctly here
        echo "";
    } else {
        echo "";
    }

} catch (PDOException $e) {
    die("Connection or query failed: " . $e->getMessage());
}
?>
