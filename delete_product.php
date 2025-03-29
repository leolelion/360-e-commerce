<?php
include 'config.php';

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("DELETE FROM Products WHERE product_id = :product_id");
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();

        $pdo->commit();

        header("Location: admin.php?page=products&status=success");
        exit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        die("Error deleting product: " . $e->getMessage());
    }
} else {
    header("Location: admin.php?page=products&status=error");
    exit();
}
?>
