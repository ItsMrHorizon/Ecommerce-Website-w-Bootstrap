<?php
require_once 'IndexDB.php';

try {
    $stmt = $pdo->prepare("
        SELECT product_id, product_name, product_price, product_img, product_stock
        FROM products
        ORDER BY product_id
    ");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $products = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>

    <script src="../assets/js/color-modes.js"></script>
    <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="carousel.css" rel="stylesheet">
    <link rel="stylesheet" href="Index.css">
</head>
<body>

<header>
    <nav>
        <div class="nav-logos">
            <img src="Logo.png" alt="Logo">
            <img src="Logo2.png" alt="Partner Logo">
        </div>

        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="admin.html">Admin</a>
            <a href="#">Contact Us</a>
        </div>

        <div class="nav-search">
            <input type="text" placeholder="Search">
            <input type="button" value="Search">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white" class="bi bi-cart" viewBox="0 0 16 16">
                <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5"/>
            </svg>
        </div>
    </nav>
</header>

<!-- Carousel -->
<main>
<section>
    <div id="myCarousel" class="carousel slide mb-6" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="2"></button>
        </div>

        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="banner1.png" class="carousel-imgs" alt="Slide 1">
                <div class="carousel-caption caption-left">
                    <h2>Your New Superpower.</h2>
                    <p>Starting at ₱32,619.00</p>
                    <a class="btn btn-lg btn-primary" href="#">Buy Now</a>
                </div>
            </div>

            <div class="carousel-item">
                <img src="banner2.png" class="carousel-img" alt="Slide 2">
                <div class="carousel-caption caption-center">
                    <h1>Available Now.</h1>
                    <p>iPhone 16 series</p>
                    <a class="btn btn-lg btn-primary" href="#">Learn more</a>
                </div>
            </div>

            <div class="carousel-item">
                <img src="banner3.png" class="carousel-imgs" alt="Slide 3">
                <div class="carousel-caption caption-right">
                    <h1>Members get 8% off!</h1>
                    <p>Want the newest trend?</p>
                    <a class="btn btn-lg btn-primary" href="#">Sign up</a>
                </div>
            </div>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
</section>

<!-- Products -->
<section class="product-section">
<?php foreach ($products as $row):
    $pid = (int)$row['product_id'];
    $pname = htmlspecialchars($row['product_name']);
    $pprice = number_format($row['product_price']);
    $pstock = (int)$row['product_stock'];
    $pimg = htmlspecialchars($row['product_img'] ?: 'img/placeholder.png');
?>
    <div class="product-card" data-product-id="<?= $pid ?>">
        <div class="product-image">
            <img src="<?= $pimg ?>" alt="<?= $pname ?>">
        </div>

        <div class="product-info">
            <h3><?= $pname ?></h3>
            <p class="price">₱<?= $pprice ?></p>
            <p class="stock">Stock: <span class="stock-value"><?= $pstock ?></span></p>

            <!-- buy-now triggers bootstrap modal -->
            <button
                type="button"
                class="btn btn-sm btn-primary buy-now"
                data-action="buy"
                data-product-id="<?= $pid ?>"
                data-product-name="<?= htmlspecialchars($row['product_name'], ENT_QUOTES) ?>"
                data-product-price="<?= htmlspecialchars($row['product_price'], ENT_QUOTES) ?>"
                data-product-stock="<?= $pstock ?>"
                data-product-img="<?= $pimg ?>"
                <?= $pstock <= 0 ? 'disabled' : '' ?>>
                Buy Now
            </button>

            <button
                type="button"
                class="btn btn-sm btn-outline-secondary add-cart"
                data-action="cart"
                data-product-id="<?= $pid ?>"
                <?= $pstock <= 0 ? 'disabled' : '' ?>>
                Add to cart
            </button>
        </div>
    </div>
<?php endforeach; ?>
</section>
</main>

<!-- BOOTSTRAP MODAL (single modal used for Buy Now) -->
<div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalName">Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="d-flex gap-4 flex-wrap">
            <div style="flex:0 0 260px;">
                <div style="width:260px;height:260px;border-radius:10px;border:1px solid #ddd;overflow:hidden;background:#f5f5f5;">
                    <img id="modalImg" src="img/placeholder.png" alt="product image" style="width:100%;height:100%;object-fit:contain;">
                </div>
            </div>
            <div style="flex:1;min-width:220px;">
                <p class="mb-1">Price: ₱<strong id="modalPrice">0.00</strong></p>
                <p class="mb-1">Available: <span id="modalStock">0</span></p>

                <div class="mt-3">
                    <label for="modalQty" class="form-label">Quantity</label>
                    <input type="number" id="modalQty" class="form-control w-25" min="1" value="1">
                </div>

                <div id="modalFeedback" class="mt-3 text-danger"></div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="modalPrimaryBtn" class="btn btn-primary">Place order</button>
      </div>
    </div>
  </div>
</div>

<footer>
    <div class="footer-content">
        <p class="footer-text">
           Copyright © 2025 Apple Inc. All rights reserved.
        </p>
        <div class="footer-badges">
            <img src="footer2.png" alt="Authorised Reseller">
            <img src="footer1.png" alt="Authorised Service Provider">
            <img src="footer3.png" alt="Authorised Learning">
            <img src="footer4.png" alt="Premium Service Provider">
        </div>
    </div>
</footer>

<script src="../assets/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const productModalEl = document.getElementById('productModal');
    const bsModal = new bootstrap.Modal(productModalEl, { keyboard: true });

    const modalName = document.getElementById('modalName');
    const modalImg = document.getElementById('modalImg');
    const modalPrice = document.getElementById('modalPrice');
    const modalStock = document.getElementById('modalStock');
    const modalQty = document.getElementById('modalQty');
    const modalFeedback = document.getElementById('modalFeedback');
    const modalPrimaryBtn = document.getElementById('modalPrimaryBtn');

    let currentProductId = null;
    let currentStock = 0;

    // Open modal when Buy Now clicked
    document.querySelectorAll('.buy-now').forEach(btn => {
        btn.addEventListener('click', function () {
            modalFeedback.textContent = '';
            modalQty.value = 1;

            currentProductId = this.getAttribute('data-product-id');
            const name = this.getAttribute('data-product-name') || '';
            const price = parseFloat(this.getAttribute('data-product-price') || '0');
            currentStock = parseInt(this.getAttribute('data-product-stock') || '0');
            const img = this.getAttribute('data-product-img') || 'img/placeholder.png';

            modalName.textContent = name;
            modalImg.src = img;
            modalImg.alt = name;
            modalPrice.textContent = price.toFixed(2);
            modalStock.textContent = currentStock;

            modalQty.max = currentStock;
            if (currentStock <= 0) {
                modalPrimaryBtn.disabled = true;
                modalFeedback.textContent = 'Out of stock';
            } else {
                modalPrimaryBtn.disabled = false;
            }

            modalPrimaryBtn.textContent = 'Place order';
            bsModal.show();
        });
    });

    // Primary button: place order
    modalPrimaryBtn.addEventListener('click', function () {
        const qty = parseInt(modalQty.value, 10) || 0;
        modalFeedback.textContent = '';
        if (!currentProductId) {
            modalFeedback.textContent = 'Product not selected.';
            return;
        }
        if (qty <= 0) {
            modalFeedback.textContent = 'Enter a valid quantity.';
            return;
        }
        if (qty > currentStock) {
            modalFeedback.textContent = 'Not enough stock available.';
            return;
        }

        modalPrimaryBtn.disabled = true;
        modalPrimaryBtn.textContent = 'Processing...';

        fetch('process_order.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ product_id: currentProductId, quantity: qty })
        })
        .then(r => r.json())
        .then(data => {
            modalPrimaryBtn.disabled = false;
            modalPrimaryBtn.textContent = 'Place order';

            if (data && data.success) {
                // update stock locally
                currentStock = Math.max(0, currentStock - qty);
                modalStock.textContent = currentStock;

                // update product card stock
                const card = document.querySelector('.product-card[data-product-id="' + currentProductId + '"]');
                if (card) {
                    const stockSpan = card.querySelector('.stock-value');
                    if (stockSpan) stockSpan.textContent = currentStock;

                    if (currentStock <= 0) {
                        // disable buttons inside the card
                        const btns = card.querySelectorAll('button');
                        btns.forEach(b => b.disabled = true);
                    }
                }

                // close modal after short delay
                modalFeedback.style.color = 'green';
                modalFeedback.textContent = data.message || 'Order placed successfully';
                setTimeout(() => {
                    modalFeedback.style.color = '';
                    modalFeedback.textContent = '';
                    bsModal.hide();
                }, 700);
            } else {
                let msg = (data && data.message) ? data.message : 'Order failed';
                if (msg === 'insufficient stock') msg = 'Not enough stock available';
                modalFeedback.style.color = '';
                modalFeedback.textContent = msg;
            }
        })
        .catch(err => {
            console.error(err);
            modalPrimaryBtn.disabled = false;
            modalPrimaryBtn.textContent = 'Place order';
            modalFeedback.textContent = 'Network or server error';
        });
    });
});
</script>

</body>
</html>
