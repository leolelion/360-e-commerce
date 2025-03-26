<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    try {
        $stmt = $pdo->prepare("SELECT * FROM Users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Store user data in session
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['user_id']; 
            $_SESSION['email'] = $user['email'];  

            header("Location: index.php");
            exit();
        } else {
            
            header("Location: login.php?error=invalid_credentials");
            exit();
        }
    } catch (PDOException $e) {
        header("Location: login.php?error=db_error");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}


