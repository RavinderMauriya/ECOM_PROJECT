<?php
require_once '../includes/db.php';

if (isset($_POST['submit'])) {
    // Form fetch
    $title = $_POST['title'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    // Image data fetch
    $image_name = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $image_size = $_FILES['image']['size'];
    $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);
    
    // Allowed image extensions (JPEG, PNG, JPG)
    $allowed_ext = array('jpg', 'jpeg', 'png');
    if (in_array($image_ext, $allowed_ext)) {
        
        // Unique naam bana ke image ko upload karna
        $image_new_name = uniqid('', true) . '.' . $image_ext;
        $image_upload_path = '../assets/img/products/' . $image_new_name;

        if (move_uploaded_file($image_tmp, $image_upload_path)) {
            // Image path database
            $image_path = 'assets/img/products/' . $image_new_name;

            // Product insert
            $sql = "INSERT INTO products (title, category, price, stock, image) VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$title, $category, $price, $stock, $image_path]);

            echo "Product added successfully!";
        } else {
            echo "Image upload failed!";
        }
    } else {
        echo "Only JPG, JPEG, and PNG images are allowed!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body>
<div class="admin-container">
    <?php include 'sidebar.php'; ?>
    
    <div class="admin-content">
        <h1>Add New Product</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Product Title:</label>
                <input type="text" name="title" required>
            </div>

            <div class="form-group">
                <label for="category">Category:</label>
                <input type="text" name="category" required>
            </div>

            <div class="form-group">
                <label for="price">Price:</label>
                <input type="text" name="price" required>
            </div>

            <div class="form-group">
                <label for="stock">Stock Quantity:</label>
                <input type="number" name="stock" required>
            </div>

            <div class="form-group">
                <label for="image">Product Image:</label>
                <input type="file" name="image" required>
            </div>

            <button type="submit" name="submit" class="submit-btn">Add Product</button>
        </form>
    </div>
</div>
</body>
</html>
