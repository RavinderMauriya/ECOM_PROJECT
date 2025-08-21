<?php
session_start();
require_once '../includes/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Login required']);
    exit;
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['product_id'], $data['quantity'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
    exit;
}

$product_id = (int) $data['product_id'];
$quantity = (int) $data['quantity'];

// Check if already exists
$stmt = $pdo->prepare("SELECT id FROM cart_items WHERE user_id = ? AND product_id = ?");
$stmt->execute([$user_id, $product_id]);
$existing = $stmt->fetch();

if ($existing) {
    // Update existing item
    $stmt = $pdo->prepare("UPDATE cart_items SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$quantity, $user_id, $product_id]);
} else {
    // Insert new item
    $stmt = $pdo->prepare("INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $product_id, $quantity]);
}

echo json_encode(['status' => 'success', 'message' => 'Added to cart']);
?>

