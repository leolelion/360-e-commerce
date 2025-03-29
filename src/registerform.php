<?php
session_start();
require_once 'config.php';


if (!isset($_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['password'])) {
    die("Error: Missing required fields.");
}


$first_name = trim($_POST['first_name']);
$last_name = trim($_POST['last_name']);
$email = trim($_POST['email']);
$password = $_POST['password'];

if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
    die("Error: All fields are required.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Error: Invalid email format.");
}


$hashed_password = password_hash($password, PASSWORD_DEFAULT);


$profile_picture = null;
$upload_dir = "uploads/profile_pictures/";

// make sure directory exists, directory permissions must be set to write to the directory
//have to enable directory permissions to the WEB SERVER not user. 
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true); // Create the directory if it doesn't exist(w permissions)
}

if (!empty($_FILES['profile_picture']['name'])) {
    $target_file = $upload_dir . basename($_FILES['profile_picture']['name']);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));


    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowed_types)) {
        die("Error: Please upload an accepted image file type (JPG, JPEG, PNG, GIF).");
    }


    $max_file_size = 5 * 1024 * 1024;
    if ($_FILES['profile_picture']['size'] > $max_file_size) {
        die("Error: File size exceeds the maximum allowed limit.");
    }


    if (!getimagesize($_FILES['profile_picture']['tmp_name'])) {
        die("Error: Uploaded file is not a valid image.");
    }


    $new_filename = uniqid("profile_", true) . "." . $imageFileType;
    $new_filename = preg_replace('/[^a-zA-Z0-9._-]/', '', $new_filename); // Remove invalid characters, necessary!
    $target_file = $upload_dir . $new_filename;

    // Moveto target directory
    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
        $profile_picture = $target_file;
    } else {
        die("Error: Failed to upload profile picture.");
    }
}

try {

    $sql = "INSERT INTO users (first_name, last_name, email, password, profile_picture) 
            VALUES (:first_name, :last_name, :email, :password, :profile_picture)";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':profile_picture', $profile_picture);

    $stmt->execute();
    //$_SESSION['loggedin'] = true;
    //$_SESSION['user_id'] = $user['user_id']; 
    //$_SESSION['email'] = $email;  
    // Redirect to index.php on success, potentially change to a success page?
    header("Location: login.php");
    
    exit();
} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    die("An error occurred. Please try again later.");
}
?>