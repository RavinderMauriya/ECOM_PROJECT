<?php
require_once '../includes/db.php';

if (!isset($_GET['id'])) {
    die('Order ID missing');
}

$order_id = (int) $_GET['id'];

// Fetch current order
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    die('Order not found');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newStatus = $_POST['status'];

    $updateStmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $updateStmt->execute([$newStatus, $order_id]);

    header("Location: admin_orders.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Order Status - Admin</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="admin-container">
    <?php include 'sidebar.php'; ?>
    <div class="admin-content">
        <h1>Edit Order Status</h1>

        <form method="POST">
            <label for="status">Select Status:</label><br><br>
            <select name="status" id="status" required>
                <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="shipped" <?= $order['status'] == 'shipped' ? 'selected' : '' ?>>Shipped</option>
                <option value="delivered" <?= $order['status'] == 'delivered' ? 'selected' : '' ?>>Delivered</option>
                <option value="cancelled" <?= $order['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
            </select>
            <br><br>
            <button type="submit" class="add-btn">Update Status</button>
        </form>
    </div>
</div>

</body>
</html>
