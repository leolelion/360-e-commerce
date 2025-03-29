<?php
include 'config.php';

try {
    $stmt = $pdo->query("SELECT category_id, category_name FROM Categories");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}

try {
    $stmt = $pdo->query("SELECT vendor_id, vendor_name FROM Vendors");
    $vendors = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $vendor_id = $_POST['vendor_id'];
    $description = trim($_POST['description']);
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    $category_id = $_POST['category_id'];
    $image_url = trim($_POST['image_url']);

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Products WHERE name = ? AND vendor_id = ?");
    $stmt->execute([$name, $vendor_id]);
    $exists = $stmt->fetchColumn();

    if ($exists > 0) {
        $message = "Error: A product with the same name and vendor already exists!";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO Products (name, vendor_id, description, price, stock_quantity, category_id, image_url) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $vendor_id, $description, $price, $stock_quantity, $category_id, $image_url]);
            $message = "✅ Product added successfully!";
        } catch (PDOException $e) {
            $message = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Add New Product</h2>
    <a href="admin_products.php">⬅ Back to Products</a>

    <?php if (isset($message)) : ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form action="add_product.php" method="POST">
        <label>Name:</label>
        <input type="text" name="name" required>

        <label>Vendor:</label>
        <select name="vendor_id" required>
            <option value="">Select Vendor</option>
            <?php foreach ($vendors as $vendor) : ?>
                <option value="<?= $vendor['vendor_id'] ?>">
                    <?= htmlspecialchars($vendor['vendor_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Description:</label>
        <textarea name="description" required></textarea>

        <label>Price:</label>
        <input type="number" name="price" step="0.01" required>

        <label>Stock Quantity:</label>
        <input type="number" name="stock_quantity" required>

        <label>Category:</label>
        <select name="category_id" required>
            <option value="">Select Category</option>
            <?php foreach ($categories as $category) : ?>
                <option value="<?= $category['category_id'] ?>">
                    <?= htmlspecialchars($category['category_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Image URL:</label>
        <input type="text" name="image_url" required>

        <button type="submit">Add Product</button>
    </form>
</body>
</html>
