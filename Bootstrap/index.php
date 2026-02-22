<?php
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
            <a href="admin.php">Admin</a>
            <a href="contact.html">Contact Us</a>
        </div>
    </nav>
</header>

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

    <!-- Product Section -->
    <section class="product-section">
        <?php
        include 'IndexDB.php';

        $stmt = $pdo->query("SELECT * FROM products ORDER BY product_id");
        while ($row = $stmt->fetch()) {
            $imagePath = "images/" . htmlspecialchars($row['product_img']);
            ?>
            <div class="product-card">
                <div class="product-image">
                    <img src="<?= $imagePath ?>" 
                         alt="<?= htmlspecialchars($row['product_name']) ?>">
                </div>
                <div class="product-info">
                    <h3><?= htmlspecialchars($row['product_name']) ?></h3>
                    <p class="price">₱<?= number_format($row['product_price'], 2) ?></p>
                    
                    <button class="buy-now btn btn-primary w-100"
                            data-id="<?= $row['product_id'] ?>"
                            data-name="<?= htmlspecialchars($row['product_name']) ?>"
                            data-price="<?= $row['product_price'] ?>"
                            data-stocks="<?= $row['product_stocks'] ?>"
                            data-image="<?= $imagePath ?>">
                        Buy Now
                    </button>
                </div>
            </div>
            <?php
        }
        ?>
    </section>
</main>

<footer>
    <div class="footer-content">
        <p class="footer-text">
            Apple Inc. &reg;<br>&copy; All Rights Reserved 1979.
        </p>
        <div class="footer-badges">
            <img src="footer2.png" alt="Authorised Reseller">
            <img src="footer1.png" alt="Authorised Service Provider">
            <img src="footer3.png" alt="Authorised Learning">
            <img src="footer4.png" alt="Premium Service Provider">
        </div>
    </div>
</footer>

<!-- Buy Now Modal - Apple Style -->
<div class="modal fade" id="buyNowModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="position:relative;">
            
            <!-- Large X close button -->
            <span class="modal-close-x" onclick="closeBuyModal()">×</span>

            <div class="modal-header">
                <h5 class="modal-title">Confirm Purchase</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <form id="buyNowForm" method="POST" action="buy.php">
                    <div class="row g-5">
                        <!-- Image Column -->
                        <div class="col-md-5">
                            <div class="img-container">
                                <img id="modalProductImg" src="" class="img-fluid" alt="">
                            </div>
                        </div>

                        <!-- Details Column -->
                        <div class="col-md-7">
                            <h3 id="modalProductName" class="fw-semibold mb-2"></h3>
                            <div class="product-price">₱<span id="modalProductPrice"></span></div>
                            
                            <input type="hidden" name="product_id" id="modalProductId">
                            <input type="hidden" name="product_name" id="modalProductNameHidden">
                            <input type="hidden" name="product_price" id="modalProductPriceHidden">
                            <input type="hidden" name="product_stocks" id="modalProductStocksHidden">

                            <div class="mb-4">
                                <span class="badge bg-success rounded-pill px-3 py-2 fs-6">In Stock: <span id="modalProductStocks"></span></span>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Quantity</label>
                                <input type="number" name="quantity" id="modalQuantity" 
                                       class="form-control" value="1" min="1" oninput="updateTotal()">
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Your Name</label>
                                <input type="text" name="customer_name" id="modalName" 
                                       class="form-control" placeholder="John Doe" required>
                            </div>

                            <div class="total-section">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-muted fs-5">Total Amount</span>
                                    <span class="fs-2 fw-semibold">₱<span id="modalTotalAmount">0.00</span></span>
                                    <input type="hidden" name="total_amount" id="modalTotalAmountHidden">
                                </div>
                                <button type="submit" class="confirm-order-btn">Confirm Order</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="../assets/dist/js/bootstrap.bundle.min.js"></script>

<script>
let buyModalInstance; // Global variable for modal instance

document.addEventListener('DOMContentLoaded', function() {
    buyModalInstance = new bootstrap.Modal(document.getElementById('buyNowModal'));

    document.querySelectorAll('.buy-now').forEach(button => {
        button.addEventListener('click', function() {
            const id     = this.dataset.id;
            const name   = this.dataset.name;
            const price  = parseFloat(this.dataset.price);
            const stocks = parseInt(this.dataset.stocks);
            const image  = this.dataset.image;

            document.getElementById('modalProductId').value = id;
            document.getElementById('modalProductName').textContent = name;
            document.getElementById('modalProductNameHidden').value = name;
            document.getElementById('modalProductImg').src = image;
            document.getElementById('modalProductPrice').textContent = price.toLocaleString('en-PH', {minimumFractionDigits: 2});
            document.getElementById('modalProductPriceHidden').value = price;
            document.getElementById('modalProductStocks').textContent = stocks;
            document.getElementById('modalProductStocksHidden').value = stocks;

            const qtyInput = document.getElementById('modalQuantity');
            qtyInput.value = 1;
            qtyInput.max = stocks;

            document.getElementById('modalName').value = '';

            updateTotal();
            buyModalInstance.show();
        });
    });
});

function closeBuyModal() {
    buyModalInstance.hide();
}

function updateTotal() {
    let quantity = parseInt(document.getElementById('modalQuantity').value) || 1;
    const maxStock = parseInt(document.getElementById('modalQuantity').max) || 999;
    
    if (quantity > maxStock) quantity = maxStock;
    if (quantity < 1) quantity = 1;
    document.getElementById('modalQuantity').value = quantity;

    const price = parseFloat(document.getElementById('modalProductPriceHidden').value) || 0;
    const total = quantity * price;

    const formattedTotal = total.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});

    document.getElementById('modalTotalAmount').textContent = formattedTotal;
    document.getElementById('modalTotalAmountHidden').value = total.toFixed(2);
}
</script>
</body>
</html>