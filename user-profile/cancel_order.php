<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['product_id'])) {
    $order_id = $_POST['order_id'];
    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['user_id'];

    // Update specific product in order_items
    $sql = "UPDATE orders 
            SET status = 'cancelled' 
            WHERE id = ? AND user_id = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$order_id, $user_id]);
}

header("Location: profile.php");
exit();
