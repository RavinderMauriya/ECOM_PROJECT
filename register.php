<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
include 'includes/db.php';

$errors = [];
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and sanitize input
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validation
    if (empty($username)) {
        $errors[] = "Username is required.";
    }

    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // Check if email already exists
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->fetch()) {
            $errors[] = "Email already registered.";
        }
    }

    // If no errors, insert new user
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        if ($stmt->execute()) {
            // Auto-login user after registration
            $_SESSION['user_id'] = $pdo->lastInsertId();

            // Redirect to homepage
            header("Location: index.php");
            exit;
        } else {
            $errors[] = "Something went wrong. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register</title>
  <link rel="stylesheet" href="assets/Authstyles.css"/>
</head>
<body>
  <div class="container">
    <div class="form-box">
      <h2>Create Account</h2>

      <!-- Show Errors -->
      <?php if (!empty($errors)): ?>
        <div class="error-box">
          <?php foreach ($errors as $error): ?>
            <p style="color:red;"><?= htmlspecialchars($error) ?></p>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <!-- Show Success Message -->
      <?php if (!empty($success)): ?>
        <div class="success-box">
          <p style="color:green;"><?= htmlspecialchars($success) ?></p>
        </div>
      <?php endif; ?>

      <!-- Registration Form -->
      <form method="POST" action="">
        <div class="input-group">
          <input type="text" name="username" placeholder="Username" required value="<?= htmlspecialchars($username ?? '') ?>" />
          <span class="icon">&#128100;</span>
        </div>
        <div class="input-group">
          <input type="email" name="email" placeholder="Email" required value="<?= htmlspecialchars($email ?? '') ?>" />
          <span class="icon">&#9993;</span>
        </div>
        <div class="input-group">
          <input type="password" name="password" placeholder="Password" required />
          <span class="icon">&#128274;</span>
        </div>
        <div class="input-group">
          <input type="password" name="confirm_password" placeholder="Confirm Password" required />
        </div>
        <button type="submit" class="btn">Register</button>
        <p>Already have an account? <a href="login.php">Login</a></p>
      </form>
    </div>
  </div>
</body>
</html>
