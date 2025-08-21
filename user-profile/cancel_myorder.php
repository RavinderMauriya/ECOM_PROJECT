<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once '../includes/db.php';

$user_id = $_SESSION['user_id'];
$order_id = isset($_GET['order_id']) ? (int) $_GET['order_id'] : 0;

// Check if the order exists and belongs to the logged-in user
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->execute([$order_id, $user_id]);
$order = $stmt->fetch();

if (!$order) {
    // If the order doesn't exist or doesn't belong to the logged-in user
    echo "Invalid order or access denied.";
    exit();
}

// Check if the order is not already cancelled
if ($order['status'] === 'cancelled') {
    echo "This order is already cancelled.";
    exit();
}

// Update the order status to 'cancelled'
$stmtUpdate = $pdo->prepare("UPDATE orders SET status = 'cancelled' WHERE id = ?");
$stmtUpdate->execute([$order_id]);

// Redirect to My Orders page with a success message
header("Location: myorder.php?status=cancelled");

exit();
?>
