<?php
require_once '../includes/db.php'; // Database connection

// Check GET id
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    //image path
    $stmt = $pdo->prepare("SELECT image FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        // Image file delete(if exists)
        $image_path = '../' . $product['image'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }

       
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$product_id]);

        header("Location: admin_products.php?message=Product Deleted Successfully");
        exit();
    } else {
        echo "Product not found!";
    }
} else {
    echo "Invalid Request!";
}
?>
