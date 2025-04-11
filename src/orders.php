<?php
include 'config.php';

try {
    $stmt = $pdo->query("SELECT o.order_id, o.total_price, o.order_status, o.created_at, u.first_name, u.last_name 
                         FROM Orders o
                         JOIN Users u ON o.user_id = u.user_id
                         ORDER BY o.created_at DESC");

    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Orders</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Admin - Manage Orders</h2>

    <table border="1">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>User</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= htmlspecialchars($order['order_id']) ?></td>
                    <td><?= htmlspecialchars($order['first_name']) ?> <?= htmlspecialchars($order['last_name']) ?></td>
                    <td>$<?= number_format($order['total_price'], 2) ?></td>
                    <td><?= htmlspecialchars($order['order_status']) ?></td>
                    <td><?= date("Y-m-d H:i:s", strtotime($order['created_at'])) ?></td>
                    <td>
                        <a href="view_order.php?id=<?= $order['order_id'] ?>">View</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
