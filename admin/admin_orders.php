<?php
require_once '../includes/db.php'; // Database connection


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Orders</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<div class="admin-container">
    <?php include 'sidebar.php'; ?>
    <div class="admin-content">
        <h1>Manage Orders</h1>

        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User ID</th>
                    <th>Total Amount</th>
                    <th>Payment Method</th>
                    <th>Status</th>
                    <th>Placed At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    $stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC");
                    while ($order = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($order['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($order['user_id']) . "</td>";
                        echo "<td>₹" . number_format($order['total_amount'], 2) . "</td>";
                        echo "<td>" . htmlspecialchars($order['payment_method']) . "</td>";
                        echo "<td>" . ucfirst(htmlspecialchars($order['status'])) . "</td>";
                        echo "<td>" . htmlspecialchars(date('d-M-Y H:i', strtotime($order['created_at']))) . "</td>";
                        echo "<td class='action-btns'>
                                <a href='view_order.php?id=" . $order['id'] . "' title='View'><i class='fas fa-eye'></i></a>
                                <a href='edit_order.php?id=" . $order['id'] . "' title='Edit Status'><i class='fas fa-edit'></i></a>
                              </td>";
                        echo "</tr>";
                    }
                } catch (PDOException $e) {
                    echo "<tr><td colspan='7'>Error fetching orders: " . $e->getMessage() . "</td></tr>";
                }
                ?>
            </tbody>
        </table>

    </div>
</div>

</body>
</html>
