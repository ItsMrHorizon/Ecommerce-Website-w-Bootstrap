<?php
header('Content-Type: application/json');

require_once 'IndexDB.php';

// Read JSON input (IMPORTANT)
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['product_id'], $input['quantity'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request'
    ]);
    exit;
}

$product_id = (int)$input['product_id'];
$quantity   = (int)$input['quantity'];

if ($product_id < 1 || $quantity < 1) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid product or quantity'
    ]);
    exit;
}

try {
    // Start transaction
    $pdo->beginTransaction();

    // Lock row + get stock
    $stmt = $pdo->prepare("
        SELECT product_stock
        FROM products
        WHERE product_id = ?
        FOR UPDATE
    ");
    $stmt->execute([$product_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        $pdo->rollBack();
        echo json_encode([
            'success' => false,
            'message' => 'Product not found'
        ]);
        exit;
    }

    $current_stock = (int)$row['product_stock'];

    if ($current_stock < $quantity) {
        $pdo->rollBack();
        echo json_encode([
            'success' => false,
            'message' => 'Not enough stock available'
        ]);
        exit;
    }

    // Update stock
    $stmt = $pdo->prepare("
        UPDATE products
        SET product_stock = product_stock - ?
        WHERE product_id = ?
    ");
    $stmt->execute([$quantity, $product_id]);

    $pdo->commit();

    echo json_encode([
        'success'   => true,
        'message'   => 'Order placed successfully',
        'new_stock' => $current_stock - $quantity
    ]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    echo json_encode([
        'success' => false,
        'message' => 'An error occurred. Please try again.'
    ]);
}
