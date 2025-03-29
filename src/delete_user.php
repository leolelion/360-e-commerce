<?php
include('config.php');

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM Users WHERE user_id = ?");
        $stmt->execute([$user_id]);

        header("Location: admin.php?page=users");
        exit();
    } catch (PDOException $e) {
        die("Delete failed: " . $e->getMessage());
    }
} else {
    die("User ID not specified.");
}
