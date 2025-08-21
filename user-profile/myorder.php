<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
require_once '../includes/db.php';
require_once '../includes/header.php';

$user_id = $_SESSION['user_id'];

$stmtUser = $pdo->prepare("SELECT username, profile_photo FROM users WHERE id = ?");
$stmtUser->execute([$user_id]);
$user = $stmtUser->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Orders</title>
  <style>
    * { box-sizing: border-box; }
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #f1f1f1;
    }
    .container {
      display: flex;
      min-height: 100vh;
    }
    .sidebar {
      width: 250px;
      background-color: #f5f5f5;
      padding: 20px;
    }
    .sidebar .profile {
      text-align: center;
      margin-bottom: 20px;
    }
    .sidebar .profile img {
      width: 100px;
      height: 100px;
      border-radius: 50%;
    }
    .sidebar .profile h3 {
      margin: 10px 0 0;
    }
    .sidebar ul {
      list-style: none;
      padding: 0;
    }
    .sidebar ul li {
      margin: 10px 0;
      padding: 10px;
      background: #e0e0e0;
      border-radius: 5px;
      cursor: pointer;
      transition: background 0.3s;
    }
    .sidebar ul li:hover {
      background-color: #ddd;
      color: #088178;
    }
    .sidebar ul li.active {
      background-color: #088178;
      color: white;
      font-weight: bold;
    }

    .orders {
      flex: 1;
      padding: 20px;
    }
    .orders h2 {
      margin-bottom: 20px;
      color: #222;
    }
    .order-card {
      background: white;
      padding: 15px;
      margin-bottom: 15px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      transition: transform 0.2s;
    }
    .order-card:hover {
      transform: scale(1.01);
    }

    .order-card-inner {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 20px;
    }

    .order-card-content {
      display: flex;
      gap: 20px;
      flex: 1;
      cursor: pointer;
    }

    .order-card-content img {
      width: 100px;
      height: 100px;
      border-radius: 8px;
      object-fit: cover;
    }

    .order-details h4 {
      margin: 0 0 10px;
    }
    .order-details p {
      margin: 4px 0;
      color: #444;
    }
    .status {
      font-weight: bold;
      color: #088178;
    }

    .order-actions {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: flex-end;
      min-width: 130px;
    }

    .btn {
      padding: 6px 12px;
      margin-bottom: 8px;
      background-color: #088178;
      color: white;
      border: none;
      border-radius: 4px;
      text-decoration: none;
      font-size: 14px;
    }
    .btn.cancel {
      background-color: #d9534f;
    }

    .no-orders {
      background: white;
      padding: 30px;
      text-align: center;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    @media (max-width: 768px) {
      .container {
        flex-direction: column;
      }
      .sidebar {
        width: 100%;
      }
      .order-card-inner {
        flex-direction: column;
        align-items: stretch;
      }
      .order-actions {
        align-items: center;
        margin-top: 10px;
      }
    }
  </style>
</head>
<body>
<div class="container">

  <?php include 'profile_sidebar.php'; ?>

  <div class="orders">
    <h2>My Orders</h2>

    <?php
    $stmtOrders = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
    $stmtOrders->execute([$user_id]);
    $orders = $stmtOrders->fetchAll();

    if (count($orders) === 0) {
        echo '<div class="no-orders"><h3>No orders found!</h3></div>';
    }

    foreach ($orders as $order) {
        $stmtItems = $pdo->prepare("SELECT oi.*, p.title, p.image FROM order_items oi
                                    JOIN products p ON oi.product_id = p.id
                                    WHERE oi.order_id = ?");
        $stmtItems->execute([$order['id']]);
        $items = $stmtItems->fetchAll();

        $stmtAddress = $pdo->prepare("SELECT * FROM addresses WHERE id = ?");
        $stmtAddress->execute([$order['address_id']]);
        $address = $stmtAddress->fetch();

        foreach ($items as $item) {
    ?>
      <div class="order-card">
        <div class="order-card-inner">
          <div class="order-card-content" onclick="window.location.href='../product_details.php?id=<?= $item['product_id'] ?>'">
            <img src="../<?= str_replace('\\', '/', $item['image']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
            <div class="order-details">
              <h4><?= htmlspecialchars($item['title']) ?></h4>
              <p>Quantity: <?= $item['quantity'] ?></p>
              <p>Price: ₹<?= number_format($item['price'], 2) ?> (Total: ₹<?= number_format($item['price'] * $item['quantity'], 2) ?>)</p>
              <p class="status">Status: <?= ucfirst($order['status']) ?></p>
              <p>Address: <?= htmlspecialchars($address['house_number'] . ', ' . $address['landmark'] . ', ' . $address['city'] . ', ' . $address['state'] . ' - ' . $address['pin_code']) ?></p>
            </div>
          </div>
          <div class="order-actions">
            <?php if ($order['status'] !== 'cancelled') : ?>
              
               <a class="btn" href="invoicebill.php?order_id=<?= $order['id'] ?>" target="_blank">Invoice Bill</a>

            <?php endif; ?>
            <?php if ($order['status'] === 'pending') : ?>
              <a class="btn cancel" href="cancel_myorder.php?order_id=<?= $order['id'] ?>" onclick="return confirm('Cancel this order?')">Cancel Order</a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php
        }
    }
    ?>
  </div>
</div>
<?php require_once '../includes/footer.php'; ?>
</body>
</html>
