<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'db.php';
$cart_count = 0;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT SUM(quantity) as total FROM cart_items WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $result = $stmt->fetch();
    $cart_count = $result['total'] ?? 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce</title>

    <!-- .Fixed path to CSS -->
    <link rel="stylesheet" href="/Ravinder/ECOM PROJECT/assets/style1.css">

    <!-- FontAwesome link -->
    <script src="https://kit.fontawesome.com/90717ff3bb.js" crossorigin="anonymous"></script>
</head>
<body>

<section id="header">
    
    <small style="color:blue; font-weight: 600;">RaviGo</small>

    <div>
        <ul id="navbar">
            <!-- Navigation with fixed paths -->
            <li><a href="/Ravinder/ECOM PROJECT/index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">Home</a></li>
            <li><a href="/Ravinder/ECOM PROJECT/shop.php" class="<?= basename($_SERVER['PHP_SELF']) == 'shop.php' ? 'active' : ''; ?>">Shop</a></li>
            <li><a href="/Ravinder/ECOM PROJECT/blog.php" class="<?= basename($_SERVER['PHP_SELF']) == 'blog.php' ? 'active' : ''; ?>">Blog</a></li>
            <li><a href="/Ravinder/ECOM PROJECT/about.php" class="<?= basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : ''; ?>">About</a></li>
            <li><a href="/Ravinder/ECOM PROJECT/contact.php" class="<?= basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>">Contact</a></li>
            <!-- for cart symbol -->
            <li id="lg-bag" style="position: relative;">
                <a href="/Ravinder/ECOM PROJECT/cart.php">
                <i class="fa-solid fa-cart-shopping"></i>
              <?php if ($cart_count > 0): ?>
                  <span class="cart-badge"><?= $cart_count ?></span>
              <?php endif; ?>
              </a>
            </li>

            <!-- Dynamic Login/Logout -->
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="/Ravinder/ECOM PROJECT/user-profile/profile.php">My Profile</a></li>
                <li><a href="/Ravinder/ECOM PROJECT/logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="/Ravinder/ECOM PROJECT/login.php">Login</a></li>
            <?php endif; ?>

            <a href="#" id="close"><i class="fas fa-times"></i></a>
        </ul>
    </div>

    <div id="mobile">
        <a href="/Ravinder/ECOM PROJECT/cart.php"><i class="fa-solid fa-cart-shopping"></i></a>
        <i id="bar" class="fas fa-bars"></i>
    </div>
</section>
