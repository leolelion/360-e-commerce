<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    die("You must be signed in to leave a review.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id']);
    $user_id = $_SESSION['user_id'];
    $rating = intval($_POST['rating']);
    $comment = trim($_POST['comment']);

    if ($rating < 1 || $rating > 5 || empty($comment)) {
        die("Invalid input.");
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO Reviews (user_id, product_id, rating, comment) 
                               VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $product_id, $rating, $comment]);
        header("Location: product.php?id=$product_id");
        exit();
    } catch (PDOException $e) {
        die("Error submitting review: " . $e->getMessage());
    }
}
?>
