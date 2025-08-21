<?php

require_once '../includes/db.php'; 

// Fetch counts
try {
    $userCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $productCount = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
    $orderCount = $pdo->query("SELECT COUNT(*) FROM orders WHERE status NOT IN ('cancelled')")->fetchColumn();
    $totalRevenue = $pdo->query("SELECT SUM(total_amount) FROM orders WHERE status NOT IN ('cancelled')")->fetchColumn();

} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'sidebar.php'; ?>

        <div class="admin-content">
            <h1>Admin Dashboard</h1>

            <div class="dashboard-cards">
                <div class="card">
                    <h3>Total Users</h3>
                    <p><?= $userCount ?></p>
                </div>
                <div class="card">
                    <h3>Total Products</h3>
                    <p><?= $productCount ?></p>
                </div>
                <div class="card">
                    <h3>Total Orders</h3>
                    <p><?= $orderCount ?></p>
                </div>
                <div class="card">
                    <h3>Total Revenue</h3>
                    <p>₹<?= number_format($totalRevenue, 2) ?></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
