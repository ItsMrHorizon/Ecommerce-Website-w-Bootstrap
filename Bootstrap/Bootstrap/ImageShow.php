<?php
$host     = 'localhost';
$username = 'root';
$password = '';
$dbname   = 'apple';

if (!isset($_GET['id'])) {
    die("No image ID specified.");
}

$id = (int)$_GET['id'];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT product_img FROM products WHERE product_id = ?");
    $stmt->execute([$id]);
    $image = $stmt->fetchColumn();

    if ($image) {
        // Output image
        header("Content-Type: image/png");
        echo $image;
    } else {
        echo "No image found for ID $id.";
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
