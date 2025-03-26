<?php
session_start();
require_once 'config.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST['change_first_name'])) {
            $new_first_name = trim($_POST['first_name']);
            if (!empty($new_first_name)) {
                $stmt = $pdo->prepare("UPDATE Users SET first_name = :first_name WHERE user_id = :user_id");
                $stmt->execute([':first_name' => $new_first_name, ':user_id' => $user_id]);
                $_SESSION['success'] = "First name updated successfully!";
            } else {
                $_SESSION['error'] = "First name cannot be empty.";
            }
        }

        if (isset($_POST['change_email'])) {
            $new_email = trim($_POST['email']);

            if (filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
                //verify email is not in use already
                $stmt = $pdo->prepare("SELECT user_id FROM Users WHERE email = :email AND user_id != :user_id");
                $stmt->execute([':email' => $new_email, ':user_id' => $user_id]);
                
                if ($stmt->rowCount() > 0) {
                    $_SESSION['error'] = "Email already in use by another account! :(";
                } else {
                    $stmt = $pdo->prepare("UPDATE Users SET email = :email WHERE user_id = :user_id");
                    $stmt->execute([':email' => $new_email, ':user_id' => $user_id]);
                    $_SESSION['success'] = "Email updated successfully!";
                }
            } else {
                $_SESSION['error'] = "Invalid email format.";
            }
        }

        if (isset($_POST['change_password'])) {
            $new_password = $_POST['password'];
            if (!empty($new_password) && strlen($new_password) >= 6) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE Users SET password = :password WHERE user_id = :user_id");
                $stmt->execute([':password' => $hashed_password, ':user_id' => $user_id]);
                $_SESSION['success'] = "Password updated successfully!";
            } else {
                $_SESSION['error'] = "Password must be at least 6 characters long.";
            }
        }
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Database error: " . $e->getMessage();
}

header("Location: profile.php");
exit();
?>
