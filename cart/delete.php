<?php
session_start();
require_once '../includes/db.php';

header('Content-Type: application/json');

// Check login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents("php://input"), true);
$product_id = (int)($data['product_id'] ?? 0);

// Validate
if (!$product_id) {
    echo json_encode(['status' => 'error', 'message' => 'Product ID missing']);
    exit;
}

// Delete from DB
$stmt = $pdo->prepare("DELETE FROM cart_items WHERE user_id = ? AND product_id = ?");
$success = $stmt->execute([$user_id, $product_id]);

if ($success) {
    echo json_encode(['status' => 'success', 'message' => 'Item removed']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Delete failed']);
}
?>
