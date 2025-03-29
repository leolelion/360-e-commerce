<?php
include 'config.php'; // Include database connection

try {
    $stmt = $pdo->query("SELECT p.product_id, p.name, p.description, p.price, p.stock_quantity, p.image_url, 
                            c.category_name, v.vendor_name 
                     FROM Products p 
                     JOIN Categories c ON p.category_id = c.category_id
                     JOIN Vendors v ON p.vendor_id = v.vendor_id");

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Products</title>
    <link rel="stylesheet" href="styles.css"> <!-- Add your CSS file -->
</head>
<body>
    <h2>Admin - Manage Products</h2>
    <a href="add_product.php">➕ Add New Product</a>

    <table border="1">
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Vendor</th>
                <th>Image</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['product_id']) ?></td>
                    <td><?= htmlspecialchars($product['vendor_name']) ?></td>
                    <td><img src="<?= htmlspecialchars($product['image_url']) ?>" alt="Image" width="50"></td>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><?= htmlspecialchars($product['description']) ?></td>
                    <td>$<?= number_format($product['price'], 2) ?></td>
                    <td><?= htmlspecialchars($product['stock_quantity']) ?></td>
                    <td><?= htmlspecialchars($product['category_name']) ?></td>
                    <td>
                        <a href="edit_product.php?id=<?= $product['product_id'] ?>">✏️ Edit</a> |
                        <a href="delete_product.php?id=<?= $product['product_id'] ?>" onclick="return confirm('Are you sure?');">❌ Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
<?php