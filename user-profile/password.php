<?php
require_once '../includes/db.php';
require_once '../includes/header.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
  header("Location: ../login.php");
  exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Change Password</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <style>
    body {
      font-family: Arial, sans-serif;
      background:#f1f1f1;
      margin: 0;
      padding: 0;
    }

    .container {
      display: flex;
      min-height: 100vh;
    }

    .content {
      flex: 1;
      padding: 30px;
    }

    .form-group {
      display: flex;
      flex-direction: column;
      margin-bottom: 20px;
    }

    label {
      font-weight: bold;
      margin-bottom: 5px;
    }

    input {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    .buttons {
      display: flex;
      gap: 10px;
    }

    .update-btn {
      background-color: #088178;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      cursor: pointer;
    }

    .cancel-btn {
      background-color: #ccc;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      cursor: pointer;
    }
  </style>
</head>
<body>

<div class="container">
  <?php include 'profile_sidebar.php'; ?>

  <div class="content">
    <h2 style="font-size:35px;">Change Password</h2><br>
    <form action="change_password.php" method="POST">
      <div class="form-group">
        <label>Old Password</label>
        <input type="password" name="old_password" placeholder="Enter old password" required />
      </div>
      <div class="form-group">
        <label>New Password</label>
        <input type="password" name="new_password" placeholder="Enter new password" required />
      </div>
      <div class="form-group">
        <label>Confirm Password</label>
        <input type="password" name="confirm_password" placeholder="Confirm new password" required />
      </div>
      <div class="buttons">
        <button type="submit" class="update-btn">Change Password</button>
        <button type="button" class="cancel-btn">Cancel</button>
      </div>
    </form>
  </div>
</div>

<?php require_once '../includes/footer.php'; ?>
</body>
</html>
