<?php 
require_once 'config.php';


if (!isset($_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['password'])) {
    die("Error: Missing required fields.");
}

$first_name = trim($_POST['first_name']);
$last_name = trim($_POST['last_name']);
$email = trim($_POST['email']);
$password = $_POST['password'];



$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Handle profile picture upload
$profile_picture = null;
if (!empty($_FILES['profile_picture']['name'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES['profile_picture']['name']); //file path to be uploaded
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowed_types)) {
        die("please upload an image file");
    }
    //TODO: handle file organization?

    
    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
        $profile_picture = $target_file;
    } else {
        die("Error: Failed to upload profile picture.");
    }
}

try {
    // Use $pdo from config.php
    $sql = "INSERT INTO users (first_name, last_name, email, password, profile_picture) 
            VALUES (:first_name, :last_name, :email, :password, :profile_picture)";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);
    
    $stmt->bindParam(':profile_picture', $profile_picture);
    $stmt->execute();

    header("Location: home.html");
    exit();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
