<?php
session_start();
require_once 'config.php';

if (!isset($_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['password'])) {
    die("Error: Missing required fields.");
}

$secret_code = 'secret';

$first_name = trim(htmlspecialchars($_POST['first_name']));
$last_name = trim(htmlspecialchars($_POST['last_name']));
$email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
$password = $_POST['password'];
$admin_code = isset($_POST['admin_code']) ? trim($_POST['admin_code']) : '';
$secret_code = 'secret';
$role = ($admin_code === $secret_code) ? 'admin' : 'user';

if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
    die("Error: All fields are required.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Error: Invalid email format.");
}

try {
    $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM Users WHERE email = :email");
    $check_stmt->bindParam(':email', $email);
    $check_stmt->execute();
    if ($check_stmt->fetchColumn() > 0) {
        die("Error: Email already registered.");
    }
} catch (PDOException $e) {
    die("Error checking email: " . $e->getMessage());
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$profile_picture = null;
$upload_dir = __DIR__ . "/../uploads/profile_pictures/";

if (!is_dir($upload_dir)) {
    if (!mkdir($upload_dir, 0777, true)) {
        error_log("Failed to create directory: " . $upload_dir);
        die("Error: Failed to create upload directory. Please check directory permissions.");
    }
    chmod($upload_dir, 0777);
}

if (!is_writable($upload_dir)) {
    error_log("Directory not writable: " . $upload_dir);
    die("Error: Upload directory is not writable. Please check directory permissions.");
}

if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['profile_picture'];
    $target_file = $upload_dir . basename($file['name']);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowed_types)) {
        die("Error: Please upload an accepted image file type (JPG, JPEG, PNG, GIF).");
    }

    $max_file_size = 5 * 1024 * 1024;
    if ($file['size'] > $max_file_size) {
        die("Error: File size exceeds the maximum allowed limit of 5MB.");
    }

    if (!getimagesize($file['tmp_name'])) {
        die("Error: Uploaded file is not a valid image.");
    }

    $new_filename = uniqid("profile_", true) . "." . $imageFileType;
    $new_filename = preg_replace('/[^a-zA-Z0-9._-]/', '', $new_filename);
    $target_file = $upload_dir . $new_filename;

    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        $profile_picture = "uploads/profile_pictures/" . $new_filename;
    } else {
        error_log("Failed to move uploaded file to: " . $target_file);
        die("Error: Failed to upload profile picture. Please check file permissions.");
    }
}

try {
    $sql = "INSERT INTO Users (first_name, last_name, email, password, profile_picture, role) 
            VALUES (:first_name, :last_name, :email, :password, :profile_picture, :role)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':profile_picture', $profile_picture);
    $stmt->bindParam(':role', $role);

    if ($stmt->execute()) {
        header("Location: login.php?success=1");
        exit();
    } else {
        throw new PDOException("Failed to execute query");
    }
} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    die("An error occurred during registration. Please try again later.");
}
?>
