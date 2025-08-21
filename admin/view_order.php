<?php
require_once '../includes/db.php';

if (!isset($_GET['id'])) {
    die('Order ID missing');
}

$order_id = (int) $_GET['id'];

// Fetch order info
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    die('Order not found');
}

// Fetch user address
$addressStmt = $pdo->prepare("SELECT * FROM addresses WHERE id = ?");
$addressStmt->execute([$order['id']]);
$address = $addressStmt->fetch(PDO::FETCH_ASSOC);

// Fetch order items
$itemsStmt = $pdo->prepare("SELECT oi.*, p.title, p.image FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
$itemsStmt->execute([$order_id]);
$orderItems = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Order - Admin</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="admin-container">
    
    <?php include 'sidebar.php'; ?>
    <div class="admin-content">
        <h1>Order Details</h1>

        <h3>Order Information</h3>
        <p><strong>Order ID:</strong> <?= htmlspecialchars($order['id']) ?></p>
        <p><strong>User ID:</strong> <?= htmlspecialchars($order['user_id']) ?></p>
        <p><strong>Total Amount:</strong> ₹<?= number_format($order['total_amount'], 2) ?></p>
        <p><strong>Payment Method:</strong> <?= htmlspecialchars($order['payment_method']) ?></p>
        <p><strong>Status:</strong> <?= ucfirst(htmlspecialchars($order['status'])) ?></p>
        <p><strong>Placed At:</strong> <?= date('d-M-Y H:i', strtotime($order['created_at'])) ?></p>

        <h3>Delivery Address</h3>
        <?php if ($address): ?>
            <p><?= htmlspecialchars($address['house_number']) ?>, <?= htmlspecialchars($address['landmark']) ?>, <?= htmlspecialchars($address['district']) ?>, <?= htmlspecialchars($address['city']) ?>, <?= htmlspecialchars($address['state']) ?> - <?= htmlspecialchars($address['pin_code']) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($address['phone_number']) ?></p>
        <?php else: ?>
            <p>No Address Found</p>
        <?php endif; ?>

        <h3>Ordered Products</h3>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Image</th>
                    <th>Quantity</th>
                    <th>Price Each</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orderItems as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['title']) ?></td>
                        <td><img src="../<?= htmlspecialchars($item['image']) ?>" width="50" height="50" alt="Product Image"></td>
                        <td><?= htmlspecialchars($item['quantity']) ?></td>
                        <td>₹<?= number_format($item['price'], 2) ?></td>
                        <td>₹<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>
</div>

</body>
</html>
