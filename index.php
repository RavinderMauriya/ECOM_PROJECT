<?php 
include 'includes/header.php';
require_once 'includes/db.php';

// Fetch products from database
try {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC LIMIT 8");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
?>

<section id="hero">
    <h4>Welcome</h4>
    <h2>Super value deals</h2>
    <h1>On all products</h1>
    <p>Save more with coupons & up to 50% off!</p>
    <a href="shop.php"><button>Shop Now</button></a>
</section>


<section id="categories">
  <a href="#mobileproducts" class="category-card">
    <img src="assets/img/products/p1.png" alt="mobile">
    <h3>Mobiles</h3>
  </a>
  <a href="#watch" class="category-card">
    <img src="assets/img/products/p2.jpeg" alt="watch">
    <h3>Watch</h3>
  </a>
  <a href="#shoes" class="category-card">
    <img src="assets/img/products/p3.jpeg" alt="shoes">
    <h3>Shoes</h3>
  </a>
  <a href="#fashion" class="category-card">
    <img src="assets/img/products/f3.jpg" alt="Fashion">
    <h3>Fashion</h3>
  </a>
</section>


<section id="product1" class="section-p1">
    <h2>Featured Products</h2>
    <p>Summer Collection New Modern Design</p>

    <div class="pro-container" >
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

                    <a href="#" class="cart" data-id="<?= $product['id'] ?>">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No products found.</p>
        <?php endif; ?>
    </div>
</section>


<section id="product1" class="section-p1">
    <h2>Mobiles Sale</h2>
    <p>Explore the latest smartphones at the best prices</p>

    <div class="pro-container" id="mobileproducts">
        <?php
        try {
            $mobileStmt = $pdo->prepare("SELECT * FROM products WHERE category = :cat ORDER BY id DESC");
            $mobileStmt->execute(['cat' => 'Mobile']);
            $mobileProducts = $mobileStmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<p>Error fetching mobile products: " . $e->getMessage() . "</p>";
        }
        ?>

        <?php if (!empty($mobileProducts)): ?>
            <?php foreach ($mobileProducts as $product): ?>
                <div class="pro" data-id="<?= (int)$product['id'] ?>">
                    <a href="product_details.php?id=<?= (int)$product['id'] ?>">
                        <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['title']) ?>">
                    </a>
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
                    <a href="#" class="cart" data-id="<?= $product['id'] ?>">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No mobile products available right now.</p>
        <?php endif; ?>
    </div>
</section>


<section id="product1" class="section-p1">
    <h2>Sale on Watch</h2>
    <p>Explore the latest Watch at the best prices</p>

    <div class="pro-container" id="watch">
        <?php
        try {
            $mobileStmt = $pdo->prepare("SELECT * FROM products WHERE category = :cat ORDER BY id DESC");
            $mobileStmt->execute(['cat' => 'watch']);
            $mobileProducts = $mobileStmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<p>Error fetching watch products: " . $e->getMessage() . "</p>";
        }
        ?>

        <?php if (!empty($mobileProducts)): ?>
            <?php foreach ($mobileProducts as $product): ?>
                <div class="pro" data-id="<?= (int)$product['id'] ?>">
                    <a href="product_details.php?id=<?= (int)$product['id'] ?>">
                        <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['title']) ?>">
                    </a>
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
                    <a href="#" class="cart" data-id="<?= $product['id'] ?>">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No watch products available right now.</p>
        <?php endif; ?>
    </div>
</section>


<section id="product1" class="section-p1">
    <h2>Sale on Shoes</h2>
    <p>Explore the latest shoes at the best prices</p>

    <div class="pro-container" id="shoes">
        <?php
        try {
            $mobileStmt = $pdo->prepare("SELECT * FROM products WHERE category = :cat ORDER BY id DESC");
            $mobileStmt->execute(['cat' => 'shoes']);
            $mobileProducts = $mobileStmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<p>Error fetching shoes products: " . $e->getMessage() . "</p>";
        }
        ?>

        <?php if (!empty($mobileProducts)): ?>
            <?php foreach ($mobileProducts as $product): ?>
                <div class="pro" data-id="<?= (int)$product['id'] ?>">
                    <a href="product_details.php?id=<?= (int)$product['id'] ?>">
                        <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['title']) ?>">
                    </a>
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
                    <a href="#" class="cart" data-id="<?= $product['id'] ?>">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No shoes products available right now.</p>
        <?php endif; ?>
    </div>
</section>

<section id="product1" class="section-p1">
    <h2>Fashion sale</h2>
    <p>Explore the latest products at the best prices</p>

    <div class="pro-container" id="fashion">
        <?php
        try {
            $mobileStmt = $pdo->prepare("SELECT * FROM products WHERE category = :cat ORDER BY id DESC");
            $mobileStmt->execute(['cat' => 'fashion']);
            $mobileProducts = $mobileStmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<p>Error fetching fashion products: " . $e->getMessage() . "</p>";
        }
        ?>

        <?php if (!empty($mobileProducts)): ?>
            <?php foreach ($mobileProducts as $product): ?>
                <div class="pro" data-id="<?= (int)$product['id'] ?>">
                    <a href="product_details.php?id=<?= (int)$product['id'] ?>">
                        <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['title']) ?>">
                    </a>
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
                    <a href="#" class="cart" data-id="<?= $product['id'] ?>">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No fashion products available right now.</p>
        <?php endif; ?>
    </div>
</section>



<section id="features" class="section-p1">
    <div class="fe-box"><img src="assets/img/feature/f1.png" alt=""><h6>Free Shipping</h6></div>
    <div class="fe-box"><img src="assets/img/feature/f2.png" alt=""><h6>Online Order</h6></div>
    <div class="fe-box"><img src="assets/img/feature/f3.png" alt=""><h6>Save Money</h6></div>
    <div class="fe-box"><img src="assets/img/feature/f4.png" alt=""><h6>Promotion</h6></div>
    <div class="fe-box"><img src="assets/img/feature/f5.png" alt=""><h6>Happy Sale</h6></div>
    <div class="fe-box"><img src="assets/img/feature/f6.png" alt=""><h6>24/7 Support</h6></div>
</section>

<section id="newsletter" class="section-p1 section-m1">
    <div class="newstext">
        <h4>Sign Up For Newsletter</h4>
        <p>Get email updates about our latest shop and <span>special offers</span>.</p>
    </div>
    <div class="form">
        <input type="text" placeholder="Your email address">
        <button class="normal">Sign Up</button>
    </div>
</section>


<?php include 'includes/footer.php'; ?>
