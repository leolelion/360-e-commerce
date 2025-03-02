<?php
session_start();
require_once 'config.php';

$user = $_POST['user_id'];
$password = $_POST['password'];

try {

    $conn = new PDO("mysql:host=$host;dbname=$dbname", $dbuser, $dbpass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

 
    $stmt = $conn->prepare("SELECT * FROM Users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();


    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);


        if (password_verify($password, $user['password'])) {
            session_start();
            //$_SESSION['email'] = $email;
            $_SESSION['loggedin'] = true; //track user session
            header("Location: home.html");
            exit(); //stop after redirect
        } else {
            echo "Wrong Password";
        }
    } else {
        echo "No user with that email";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close connection
$conn = null;
?>
