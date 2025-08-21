<?php
session_start();
require_once 'includes/db.php';

$user_id = $_SESSION['user_id'] ?? null;
$cartItems = [];
$total = 0;

if ($user_id) {
    $stmt = $pdo->prepare("SELECT c.*, p.title, p.image, p.price 
                           FROM cart_items c 
                           JOIN products p ON c.product_id = p.id 
                           WHERE c.user_id = ?");
    $stmt->execute([$user_id]);
    $cartItems = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="assets/style1.css">
    <script src="https://kit.fontawesome.com/90717ff3bb.js"></script>
</head>
<body>

<?php include 'includes/header.php'; ?>

<section id="page-header" class="about-header">
    <h2>#Cart</h2>
    <p>Review your selected items before checkout!</p>
</section>

<section id="cart" class="section-p1">
    <table width="100%">
        <thead>
            <tr>
                <td>Remove</td>
                <td>Image</td>
                <td>Product</td>
                <td>Price</td>
                <td>Quantity</td>
                <td>Subtotal</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cartItems as $item):
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
            ?>
            <tr>
                <td>
                    <a href="cart/delete.php?product_id=<?= $item['product_id'] ?>">
                        <i class="fa-regular fa-circle-xmark"></i>
                    </a>
                </td>
                <td><img src="<?= htmlspecialchars($item['image']) ?>" width="50"></td>
                <td><?= htmlspecialchars($item['title']) ?></td>
                <td>$<?= number_format($item['price'], 2) ?></td>
                <td>
                    <input type="number" min="1" value="<?= $item['quantity'] ?>" 
                           data-product-id="<?= $item['product_id'] ?>" class="cart-qty" />
                </td>
                <td>$<?= number_format($subtotal, 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

<section id="cart-add" class="section-p1">
    <div id="coupon">
        <h3>Apply Coupon</h3>
        <div>
            <input type="text" placeholder="Enter Your Coupon">
            <button class="normal">Apply</button>
        </div>
    </div>

    <div id="subtotal">
        <h3>Cart Totals</h3>
        <table>
            <tr><td>Cart Subtotal</td><td>$<?= number_format($total, 2) ?></td></tr>
            <tr><td>Shipping</td><td>Free</td></tr>
            <tr><td><strong>Total</strong></td><td><strong>$<?= number_format($total, 2) ?></strong></td></tr>
        </table>
        <a href="checkout.php"><button class="normal">Proceed to checkout</button></a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
</body>
</html>
