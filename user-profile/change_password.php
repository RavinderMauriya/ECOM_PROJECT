<?php
session_start();
require_once '../includes/db.php'; // This correctly sets $pdo

// Check if user is logged in
$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    header("Location: ../login.php");
    exit();
}

// Collect form inputs
$oldPassword = $_POST['old_password'] ?? '';
$newPassword = $_POST['new_password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';
$errors = [];

// Validate input
if (empty($oldPassword) || empty($newPassword) || empty($confirmPassword)) {
    $errors[] = "All password fields are required.";
}
if ($newPassword !== $confirmPassword) {
    $errors[] = "New passwords do not match.";
}

// Proceed if no validation errors
if (empty($errors)) {
    try {
        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();

        if ($user && password_verify($oldPassword, $user['password'])) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $update = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update->execute([$hashedPassword, $userId]);

            $_SESSION['success'] = "Password changed successfully.";
        } else {
            $errors[] = "Old password is incorrect.";
        }
    } catch (PDOException $e) {
        $errors[] = "Something went wrong: " . $e->getMessage();
    }
}

// Store errors in session and redirect
if (!empty($errors)) {
    $_SESSION['error'] = implode("<br>", $errors);
}

header("Location: profile.php");
exit();
?>
