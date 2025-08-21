<?php
include('includes/db.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$address_stmt = $pdo->prepare("SELECT * FROM addresses WHERE user_id = :user_id");
$address_stmt->execute([':user_id' => $user_id]);
$addresses = $address_stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_method = $_POST['payment_method'];
    $address_id = $_POST['address_id'];

    if (empty($address_id)) {
        $house_number = $_POST['house_number'];
        $landmark = $_POST['landmark'];
        $district = $_POST['district'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $pin_code = $_POST['pin_code'];
        $phone_number = $_POST['phone_number'];

        if ($house_number && $landmark && $district && $city && $state && $pin_code && $phone_number) {
            $insert_address = "INSERT INTO addresses (user_id, house_number, landmark, district, city, state, pin_code, phone_number)
                               VALUES (:user_id, :house_number, :landmark, :district, :city, :state, :pin_code, :phone)";
            $stmt = $pdo->prepare($insert_address);
            $stmt->execute([
                ':user_id' => $user_id,
                ':house_number' => $house_number,
                ':landmark' => $landmark,
                ':district' => $district,
                ':city' => $city,
                ':state' => $state,
                ':pin_code' => $pin_code,
                ':phone' => $phone_number
            ]);
            $address_id = $pdo->lastInsertId();
        } else {
            echo "Please fill all new address fields.";
            exit;
        }
    }

    try {
        $pdo->beginTransaction();

        $cart_stmt = $pdo->prepare("SELECT c.*, p.price FROM cart_items c JOIN products p ON c.product_id = p.id WHERE c.user_id = :user_id");
        $cart_stmt->execute([':user_id' => $user_id]);
        $cart_items = $cart_stmt->fetchAll();

        if (count($cart_items) === 0) {
            echo "Your cart is empty.";
            exit;
        }

        $total_amount = 0;
        foreach ($cart_items as $item) {
            $total_amount += $item['price'] * $item['quantity'];
        }

        $order_stmt = $pdo->prepare("INSERT INTO orders (user_id, address_id, total_amount, payment_method, status)
                                     VALUES (:user_id, :address_id, :total_amount, :payment_method, 'pending')");
        $order_stmt->execute([
            ':user_id' => $user_id,
            ':address_id' => $address_id,
            ':total_amount' => $total_amount,
            ':payment_method' => $payment_method
        ]);

        $order_id = $pdo->lastInsertId();

        foreach ($cart_items as $item) {
            $item_stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price)
                                        VALUES (:order_id, :product_id, :quantity, :price)");
            $item_stmt->execute([
                ':order_id' => $order_id,
                ':product_id' => $item['product_id'],
                ':quantity' => $item['quantity'],
                ':price' => $item['price']
            ]);
        }

        $delete_cart = $pdo->prepare("DELETE FROM cart_items WHERE user_id = :user_id");
        $delete_cart->execute([':user_id' => $user_id]);

        $pdo->commit();
        header("Location: thank_you.php?order_id=" . $order_id);
        exit;
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Order failed: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            background: #f1f3f6;
        }

        .checkout-container {
            max-width: 600px;
            margin: 30px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        h2, h3 {
            margin-top: 0;
            color: #333;
        }

        select, input[type="text"], button {
            width: 100%;
            padding: 10px 12px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
        }

        select:focus, input[type="text"]:focus {
            outline: none;
            border-color: #2874f0;
        }

        button {
            background: #2874f0;
            color: white;
            font-weight: bold;
            border: none;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #0f5fc2;
            cursor: pointer;
        }

        @media screen and (max-width: 600px) {
            .checkout-container {
                margin: 10px;
                padding: 15px;
            }

            h2 {
                font-size: 22px;
            }

            h3 {
                font-size: 18px;
            }
        }
    </style>
</head>
<body>
    <div class="checkout-container">
        <h2>Checkout</h2>
        <form method="POST" action="">
            <h3>Select Saved Address</h3>
            <select name="address_id">
                <option value="">-- Select Address --</option>
                <?php foreach ($addresses as $address): ?>
                    <option value="<?= $address['id'] ?>">
                        <?= htmlspecialchars($address['house_number']) ?>, <?= htmlspecialchars($address['city']) ?>, <?= htmlspecialchars($address['state']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <h3>Or Enter New Address</h3>
            <input type="text" name="house_number" placeholder="House No">
            <input type="text" name="landmark" placeholder="Landmark">
            <input type="text" name="district" placeholder="District">
            <input type="text" name="city" placeholder="City">
            <input type="text" name="state" placeholder="State">
            <input type="text" name="pin_code" placeholder="Pin Code">
            <input type="text" name="phone_number" placeholder="Phone Number">

            <h3>Payment Method</h3>
            <select name="payment_method" required>
                <option value="">-- Select Payment --</option>
                <option value="Cash on Delivery">Cash on Delivery</option>
                <option value="Credit Card">Credit Card</option>
                <option value="Net Banking">Net Banking</option>
            </select>

            <button type="submit">Place Order</button>
        </form>
    </div>
</body>
</html>
