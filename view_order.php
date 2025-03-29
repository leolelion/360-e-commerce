<?php
include 'config.php';

if (isset($_GET['id'])) {
    $order_id = $_GET['id'];

    try {
        $stmt = $pdo->prepare("SELECT o.order_id, o.total_price, o.order_status, o.created_at, 
                                       u.first_name, u.last_name 
                                FROM Orders o
                                JOIN Users u ON o.user_id = u.user_id
                                WHERE o.order_id = :order_id");
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->execute();

        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt_items = $pdo->prepare("SELECT oi.order_item_id, p.name AS product_name, oi.quantity, oi.unit_price, 
                                            (oi.quantity * oi.unit_price) AS item_total
                                     FROM OrderItems oi
                                     JOIN Products p ON oi.product_id = p.product_id
                                     WHERE oi.order_id = :order_id");
        $stmt_items->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt_items->execute();

        $order_items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
} else {
    die("Order ID is missing.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Order - Admin</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Order Details for Order ID: <?= htmlspecialchars($order['order_id']) ?></h2>

    <p><strong>User:</strong> <?= htmlspecialchars($order['first_name']) ?> <?= htmlspecialchars($order['last_name']) ?></p>
    <p><strong>Total Price:</strong> $<?= number_format($order['total_price'], 2) ?></p>
    <p><strong>Status:</strong> <?= htmlspecialchars($order['order_status']) ?></p>
    <p><strong>Created At:</strong> <?= date("Y-m-d H:i:s", strtotime($order['created_at'])) ?></p>

    <h3>Order Items</h3>
    <table border="1">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($order_items as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['product_name']) ?></td>
                    <td><?= htmlspecialchars($item['quantity']) ?></td>
                    <td>$<?= number_format($item['unit_price'], 2) ?></td>
                    <td>$<?= number_format($item['item_total'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="admin.php?page=orders">Back to Orders</a>
</body>
</html>
