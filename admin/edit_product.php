<?php
require_once '../includes/db.php';

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        echo "Product not found!";
        exit;
    }

    if (isset($_POST['submit'])) {
        $title = $_POST['title'];
        $category = $_POST['category'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];

        if ($_FILES['image']['name']) {
            $image_name = $_FILES['image']['name'];
            $image_tmp = $_FILES['image']['tmp_name'];
            $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);
            $allowed_ext = array('jpg', 'jpeg', 'png');

            if (in_array($image_ext, $allowed_ext)) {
               
                $image_new_name = uniqid('', true) . '.' . $image_ext;
                $image_upload_path = '../assets/img/products/' . $image_new_name;

                if (move_uploaded_file($image_tmp, $image_upload_path)) {
                    
                    $image_path = 'assets/img/products/' . $image_new_name;
                } else {
                    echo "Image upload failed!";
                }
            } else {
                echo "Only JPG, JPEG, and PNG images are allowed!";
            }
        } else {
           
            $image_path = $product['image'];
        }

      
        $sql = "UPDATE products SET title = ?, category = ?, price = ?, stock = ?, image = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$title, $category, $price, $stock, $image_path, $product_id]);

        echo "Product updated successfully!";
    }
} else {
    echo "No product ID found!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
<div class="admin-container">
    <?php include 'sidebar.php'; ?>
    <div class="admin-content">
        <h1>Edit Product</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Product Title:</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($product['title']); ?>" required>
            </div>

            <div class="form-group">
                <label for="category">Category:</label>
                <input type="text" name="category" value="<?php echo htmlspecialchars($product['category']); ?>" required>
            </div>

            <div class="form-group">
                <label for="price">Price:</label>
                <input type="text" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
            </div>

            <div class="form-group">
                <label for="stock">Stock Quantity:</label>
                <input type="number" name="stock" value="<?php echo htmlspecialchars($product['stock']); ?>" required>
            </div>

            <div class="form-group">
                <label for="image">Product Image:</label>
                <input type="file" name="image">
                <img src="../<?php echo $product['image']; ?>" width="100" alt="Current Image">
            </div>

            <button type="submit" name="submit" class="submit-btn">Update Product</button>
        </form>
    </div>
</div>
</body>
</html>
