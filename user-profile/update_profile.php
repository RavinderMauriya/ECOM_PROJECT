<?php
session_start();
require_once '../includes/db.php'; // path check karlo agar move kiya ho

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$profile_photo = $_FILES['profile_photo'] ?? null;

// Validate
$errors = [];
if (empty($username)) $errors[] = "Username is required";
if (empty($email)) $errors[] = "Email is required";

// Handle image upload
$uploadedPhotoName = null;
if ($profile_photo && $profile_photo['error'] === UPLOAD_ERR_OK) {
    $targetDir = '../uploads/';
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $extension = pathinfo($profile_photo['name'], PATHINFO_EXTENSION);
    $uploadedPhotoName = 'profile_' . time() . '.' . strtolower($extension);
    $targetFile = $targetDir . $uploadedPhotoName;

    move_uploaded_file($profile_photo['tmp_name'], $targetFile);

    // Optional: Delete old image if not default
    $stmt = $pdo->prepare("SELECT profile_photo FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $old = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($old && $old['profile_photo'] && file_exists('../uploads/' . $old['profile_photo'])) {
        unlink('../uploads/' . $old['profile_photo']);
    }
}

// Update DB
try {
    if (empty($errors)) {
        if ($uploadedPhotoName) {
            $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, profile_photo = ? WHERE id = ?");
            $stmt->execute([$username, $email, $uploadedPhotoName, $user_id]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
            $stmt->execute([$username, $email, $user_id]);
        }

        $_SESSION['success'] = "Profile updated successfully!";
        header("Location: profile.php");
        exit;
    } else {
        $_SESSION['errors'] = $errors;
        header("Location: profile.php");
        exit;
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
