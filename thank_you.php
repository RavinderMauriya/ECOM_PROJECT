<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'includes/db.php';

if (!isset($_GET['order_id']) || !isset($_SESSION['user_id'])) {
    echo "Invalid access!";
    exit;
}

$order_id = $_GET['order_id'];
$user_id = $_SESSION['user_id'];

$order_sql = "SELECT 
                orders.id AS order_id, 
                orders.payment_method, 
                orders.total_amount, 
                addresses.house_number, 
                addresses.landmark, 
                addresses.district, 
                addresses.city, 
                addresses.state, 
                addresses.pin_code, 
                addresses.phone_number
              FROM orders 
              JOIN addresses ON orders.address_id = addresses.id 
              WHERE orders.id = :order_id AND orders.user_id = :user_id";

$stmt = $pdo->prepare($order_sql);
$stmt->execute([
    ':order_id' => $order_id,
    ':user_id' => $user_id
]);

$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "Order not found!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You!</title>
    <style>
        /* Basic reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background-color: #f4f4f4;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            opacity: 0;
            animation: fadeIn 1s forwards;
        }

        h2 {
            color: #2e7d32;
            margin-bottom: 20px;
            animation: slideIn 1s ease-out;
        }

        p {
            font-size: 18px;
            margin: 10px 0;
            line-height: 1.6;
        }

        strong {
            color: #37474f;
        }

        h3 {
            margin-top: 25px;
            color: #424242;
            font-size: 20px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 8px;
        }

        .shipping-address {
            background-color: #fafafa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 10px;
            line-height: 1.6;
        }

        a.continue-btn {
            display: inline-block;
            margin-top: 25px;
            padding: 12px 24px;
            background-color: #2874f0;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            transition: background 0.3s;
        }

        a.continue-btn:hover {
            background-color: #0b58d3;
            text-decoration: none;
        }

        /* Fade In animation */
        @keyframes fadeIn {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }

        /* Slide In animation */
        @keyframes slideIn {
            0% {
                transform: translateY(-20px);
            }
            100% {
                transform: translateY(0);
            }
        }

        /* Media query for mobile responsiveness */
        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }

            p {
                font-size: 16px;
            }

            h3 {
                font-size: 18px;
            }

            a.continue-btn {
                width: 100%;
                text-align: center;
                padding: 12px 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Thank you for your order! 🎉</h2>

        <p><strong>Order ID:</strong> #<?= htmlspecialchars($order['order_id']) ?></p>
        <p><strong>Payment Method:</strong> <?= htmlspecialchars($order['payment_method']) ?></p>
        <p><strong>Total Paid:</strong> ₹<?= htmlspecialchars($order['total_amount']) ?></p>

        <h3>Shipping Address:</h3>
        <div class="shipping-address">
            <?= htmlspecialchars($order['house_number']) ?>,
            <?= htmlspecialchars($order['landmark']) ?>,<br>
            <?= htmlspecialchars($order['district']) ?>,
            <?= htmlspecialchars($order['city']) ?>,<br>
            <?= htmlspecialchars($order['state']) ?> - <?= htmlspecialchars($order['pin_code']) ?><br>
            Phone: <?= htmlspecialchars($order['phone_number']) ?>
        </div>

        <a class="continue-btn" href="index.php">Continue Shopping</a>
    </div>
</body>
</html>
