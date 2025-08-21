<?php
include 'includes/header.php';
require_once 'includes/db.php';

// Fetch all products
try {
    $stmt = $pdo->query("SELECT * FROM products");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
?>

<section id="page-header">
    <h2>#stayhome</h2>
    <p>Save more with coupons & up to 50% off!</p>
</section>

<section id="product1" class="section-p1">
    <h2>Our Products</h2>
    <p>Summer Collection New Modern Design</p>

    <div class="pro-container">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="pro" data-id="<?= (int)$product['id'] ?>">
                     <a href="product_details.php?id=<?= (int)$product['id'] ?>"><img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['title']) ?>"></a>

                    <div class="dec">
                        <span><?= htmlspecialchars($product['brand']) ?></span>
                        <h5><?= htmlspecialchars($product['title']) ?></h5>
                        <div class="star">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <h4>₹<?= number_format($product['price'], 2) ?></h4>
                    </div>

                    <!-- This is important for JS to trigger add to cart -->
                    <a href="#" class="cart" data-id="<?= (int)$product['id'] ?>">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No products found.</p>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
