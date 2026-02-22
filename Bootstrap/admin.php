<?php
require_once 'IndexDB.php';

// Create img/ folder if needed
$uploadDir = 'img/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

// Handle Add / Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    try {
        $name  = trim($_POST['name']);
        $price = floatval($_POST['price']);
        $stock = intval($_POST['stock']);
        $id    = isset($_POST['id']) ? intval($_POST['id']) : null;

        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $newFilename = uniqid('prod_') . '.' . $ext;
            $target = $uploadDir . $newFilename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $imagePath = 'img/' . $newFilename;
            }
        }

        if ($action === 'add') {
            $stmt = $pdo->prepare("INSERT INTO products (product_name, product_price, product_img, product_stocks) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $price, $imagePath, $stock]);
        } elseif ($action === 'edit') {
            if ($imagePath) {
                $stmt = $pdo->prepare("UPDATE products SET product_name=?, product_price=?, product_img=?, product_stocks=? WHERE product_id=?");
                $stmt->execute([$name, $price, $imagePath, $stock, $id]);
            } else {
                $stmt = $pdo->prepare("UPDATE products SET product_name=?, product_price=?, product_stocks=? WHERE product_id=?");
                $stmt->execute([$name, $price, $stock, $id]);
            }
        }
        header("Location: admin.php?success=1");
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE product_id = ?");
    $stmt->execute([$_POST['delete_id']]);
    header("Location: admin.php?deleted=1");
    exit;
}

// Fetch products
$stmt = $pdo->prepare("SELECT product_id, product_name, product_price, product_img, product_stocks FROM products ORDER BY product_id");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Inventory</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<nav>
    <div class="nav-content">
        <div><img src="Logo.png" alt="Apple"></div>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="admin.php">Admin</a>
            <a href="contact.html">Contact Us</a>
        </div>
    </div>
</nav>

<div class="main-container">
    <div class="page-header">
        <h1>Manage Inventory</h1>
        <button id="addBtn">+ Add New Product</button>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <p class="success">✓ Product saved successfully!</p>
    <?php endif; ?>
    <?php if (isset($_GET['deleted'])): ?>
        <p class="success">✓ Product deleted successfully!</p>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $row):
                $imgSrc = $row['product_img'] ? htmlspecialchars($row['product_img']) : 'img/placeholder.png';
            ?>
            <tr>
                <td><?= $row['product_id'] ?></td>
                <td><img src="<?= $imgSrc ?>" class="product-img" onerror="this.src='img/placeholder.png';"></td>
                <td><?= htmlspecialchars($row['product_name']) ?></td>
                <td>₱<?= number_format($row['product_price']) ?></td>
                <td><?= $row['product_stocks'] ?></td>
                <td class="actions">
                    <button class="edit" onclick="editProduct(<?= $row['product_id'] ?>, '<?= addslashes($row['product_name']) ?>', <?= $row['product_price'] ?>, <?= $row['product_stocks'] ?>)">Edit</button>
                    <button class="delete" onclick="deleteProduct(<?= $row['product_id'] ?>)">Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Improved Modal -->
<div id="modal" class="modal">
    <div class="modal-content">
        <h3 id="modalTitle">Add New Product</h3>
        <form id="productForm" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" id="formAction" value="add">
            <input type="hidden" name="id" id="editId" value="">

            <label>Product Name</label>
            <input type="text" id="name" name="name" required>

            <label>Price (₱)</label>
            <input type="number" id="price" name="price" step="0.01" required>

            <label>Stock Quantity</label>
            <input type="number" id="stock" name="stock" required min="0">

            <label>Product Image <small>(optional)</small></label>
            <input type="file" id="image" name="image" accept="image/*">
            <small id="imageNote" style="display:none; color:#666;">Leave blank to keep current image</small>

            <div class="modal-actions">
                <button type="submit" class="save">Save Product</button>
                <button type="button" id="cancelBtn" class="cancel">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Clean White Footer -->
<footer>
    <div class="footer-content">
        <p> Apple Inc. &reg;<br>&copy; All Rights Reserved 1979.</p>
    </div>
</footer>

<script src="admin.js">
</script>
</body>
</html>