<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false]);
    exit;
}

$product_id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);

if (!$product_id) {
    echo json_encode(['success' => false]);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM Cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$_SESSION['user_id'], $product_id]);

    $stmt = $pdo->prepare("SELECT SUM(p.price * c.quantity) as subtotal FROM Cart c JOIN Products p ON c.product_id = p.product_id WHERE c.user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $result = $stmt->fetch();
    
    echo json_encode(['success' => true, 'subtotal' => $result['subtotal'] ?? 0]);
} catch (PDOException $e) {
    echo json_encode(['success' => false]);
}
?>