<?php
require_once '../includes/db.php';

if (!isset($_GET['order_id'])) {
    die("Order ID is missing.");
}

$order_id = intval($_GET['order_id']);

// Fetch order, address, and user

$stmt = $pdo->prepare("
    SELECT o.*, a.*, u.username
    FROM orders o
    JOIN addresses a ON o.address_id = a.id
    JOIN users u ON o.user_id = u.id
    WHERE o.id = ?
");




$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$order) {
    die("Invalid order.");
}

// Fetch order items
$stmtItems = $pdo->prepare("
    SELECT oi.*, p.title
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?
");
$stmtItems->execute([$order_id]);
$items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Invoice - Order #<?= $order['id'] ?></title>
    <style>
        body { font-family: 'Arial', sans-serif; margin: 0; padding: 20px; color: #333; }
        .invoice-box { max-width: 800px; margin: auto; border: 1px solid #eee; padding: 30px; }
        .invoice-header { display: flex; justify-content: space-between; align-items: center; }
        .invoice-header .logo { width: 150px; height: 80px; border: 1px dashed #ccc; display: flex; align-items: center; justify-content: center; font-size: 12px; color: #999; }
        h2 { margin: 10px 0; text-align: center; }
        .section-title { font-weight: bold; margin-top: 30px; border-bottom: 1px solid #ddd; padding-bottom: 5px; }
        .info { margin-top: 10px; line-height: 1.6; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table th, table td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        .right { text-align: right; }
        .total { font-weight: bold; background-color: #f9f9f9; }
        .print-button { text-align: center; margin-top: 30px; }
        .print-button button { padding: 10px 20px; font-size: 16px; cursor: pointer; }
        @media print {
            .print-button { display: none; }
        }
    </style>
</head>
<body>

<div class="invoice-box">
    <div class="invoice-header">
        <div class="logo">
    <img src="../assets/img/logo1.png" alt="Company Logo" style="max-width: 100%; height: 80px;">
</div>

        <div>
            <strong>Invoice #: </strong><?= $order['id'] ?><br>
            <strong>Date: </strong><?= date("d M Y", strtotime($order['created_at'])) ?><br>
        </div>
    </div>

    <h2>Tax Invoice</h2>

    <div class="section-title">From:</div>
    <div class="info">
        12 for testing site<br>
        Email: ravindermauriya@gmail.com<br>
        Phone: 1234567890
    </div>

    <div class="section-title">To:</div>
    <div class="info">
        <?= htmlspecialchars($order['username']) ?><br>
        <?= $order['house_number'] ?>, <?= $order['landmark'] ?><br>
        <?= $order['city'] ?>, <?= $order['state'] ?> - <?= $order['pin_code'] ?><br>
        Phone: <?= $order['phone_number'] ?>
    </div>

    <div class="section-title">Order Summary:</div>
    <div class="info">
        <strong>Order ID:</strong> <?= $order['id'] ?><br>
        <strong>Payment Method:</strong> <?= $order['payment_method'] ?><br>
        <strong>Status:</strong> <?= ucfirst($order['status']) ?>
    </div>

    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Unit Price (₹)</th>
                <th>Tax (₹)</th>
                <th>Total (₹)</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $grandTotal = 0;
        foreach ($items as $item):
            $itemTotal = $item['quantity'] * $item['price'];
            $tax = 0;
            $grandTotal += $itemTotal;
        ?>
            <tr>
                <td><?= htmlspecialchars($item['title']) ?></td>
                <td><?= $item['quantity'] ?></td>
                <td class="right"><?= number_format($item['price'], 2) ?></td>
                <td class="right"><?= number_format($tax, 2) ?></td>
                <td class="right"><?= number_format($itemTotal, 2) ?></td>
            </tr>
        <?php endforeach; ?>
            <tr class="total">
                <td colspan="4" class="right">Grand Total</td>
                <td class="right">₹<?= number_format($grandTotal, 2) ?></td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">Notes:</div>
    <div class="info">
        Thank you for shopping with us! This is a system-generated invoice.
    </div>
</div>

<div class="print-button">
    <button onclick="window.print()">Download Invoice as PDF</button>
</div>

</body>
</html>
