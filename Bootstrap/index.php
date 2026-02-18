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
            <a href="admin.html">Admin</a>
            <a href="#">Contact Us</a>
        </div>

        <div class="nav-search">
            <input type="text" placeholder="Search">
            <input type="button" value="Search">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white" class="bi bi-cart" viewBox="0 0 16 16" value="cart">
  <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l1.313 7h8.17l1.313-7zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
</svg>
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
        <!-- Database -->
   <?php
include 'IndexDB.php';

$stmt = $pdo->query("SELECT * FROM products ORDER BY product_id");
while ($row = $stmt->fetch()) {
    ?>
    <div class="product-card">
        <div class="product-image">
            <img src="data:image/jpeg;base64,<?= base64_encode($row['product_img']) ?>" 
                 alt="<?= htmlspecialchars($row['product_name']) ?>">
        </div>
        <div class="product-info">
            <h3><?= htmlspecialchars($row['product_name']) ?></h3>
            <p class="price">₱<?= number_format($row['product_price']) ?></p>
            <button class="buy-now">Buy Now</button>
            <button class="add-cart">Add to cart</button>
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

<script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>