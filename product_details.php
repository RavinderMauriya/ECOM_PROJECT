<?php
require_once 'includes/db.php';
include 'includes/header.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = (int) $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        die("Product not found.");
    }
} else {
    die("Invalid Product ID.");
}
?>

<section id="prodetails" class="section-p1">
    <div class="single-pro-image">
        <!-- Just one main image -->
        <img src="<?= htmlspecialchars($product['image']) ?>" width="100%" alt="">
    </div>

    <!-- <div class="single-pro-details" data-id="<?= (int)$product['id'] ?>"> -->

    <div class="single-pro-details pro" data-id="<?= (int)$product['id'] ?>">

        <h6>Home / <?= htmlspecialchars($product['category']) ?></h6>
        <h4><?= htmlspecialchars($product['title']) ?></h4>
        <h2>₹<?= number_format($product['price'], 2) ?></h2>
        
        <input type="number" value="1">
        <button class="normal cart" data-id="<?= (int)$product['id'] ?>">
            <i class="fa-solid fa-cart-shopping"></i> Add To Cart
        </button>

    

        <h4>Product Details</h4>
        <span><?= htmlspecialchars($product['description']) ?></span>
    </div>
</section>


<?php include 'includes/footer.php'; ?>
