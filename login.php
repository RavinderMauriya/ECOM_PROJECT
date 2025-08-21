<?php
session_start();
require_once 'includes/db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id, password, role FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role']; // Role

            if ($user['role'] === 'admin') {
                header("Location: admin/admin.php"); // Admin dashboard
                exit;
            } else {
                // Load cart items into session
                $cartStmt = $pdo->prepare("SELECT c.*, p.title, p.price, p.image 
                                           FROM cart_items c 
                                           JOIN products p ON c.product_id = p.id 
                                           WHERE c.user_id = ?");
                $cartStmt->execute([$user['id']]);
                $_SESSION['cart'] = $cartStmt->fetchAll();

                header("Location: index.php"); // Normal user homepage
                exit;
            }
        } else {
            $errors[] = "Invalid email or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login</title>
  <link rel="stylesheet" href="assets/Authstyles.css"/>
</head>
<body>
  <div class="container">
    <div class="form-box">
      <h2 id="form-title">Login</h2>

      <?php if (!empty($errors)): ?>
        <div class="error-box">
          <?php foreach ($errors as $error): ?>
            <p style="color:red;"><?= htmlspecialchars($error) ?></p>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="">
        <div class="input-group">
          <input type="email" name="email" placeholder="Email" required />
          <span class="icon">&#9993;</span>
        </div>
        <div class="input-group">
          <input type="password" name="password" placeholder="Password" required />
          <span class="icon">&#128274;</span>
        </div>
        <button type="submit" class="btn">Login</button>
        <p>Don't have an account? <a href="register.php">Register</a></p>
      </form>
    </div>
  </div>
</body>
</html>

