<?php
$host     = 'localhost';
$username = 'root';
$password = '';
$dbname   = 'apple';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("ALTER TABLE products MODIFY COLUMN product_img LONGBLOB");

    if ($pdo->query("SELECT COUNT(*) FROM products")->fetchColumn() == 0) {
        $pdo->exec("
            INSERT INTO products (product_name, product_price, product_img, product_stock) VALUES
            ('iPhone 17 Pro Max', 108990, NULL, 50),
            ('iPhone 17 Pro',     94990,  NULL, 75),
            ('iPhone 13',         32619,  NULL, 100)
        ");
    }

} catch (PDOException $e) {
    die("Connection or query failed: " . $e->getMessage());
}
?>