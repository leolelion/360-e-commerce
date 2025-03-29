<?php
include 'config.php';

// Check if the product ID is passed as a query parameter
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    try {
        // Fetch the product details from the database
        $stmt = $pdo->prepare("SELECT p.product_id, p.name, p.description, p.price, p.stock_quantity, p.image_url, 
                                        p.category_id, p.vendor_id, c.category_name, v.vendor_name 
                               FROM Products p 
                               JOIN Categories c ON p.category_id = c.category_id
                               JOIN Vendors v ON p.vendor_id = v.vendor_id
                               WHERE p.product_id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            die("Product not found.");
        }

        // Fetch categories and vendors for the dropdowns
        $categories_stmt = $pdo->query("SELECT * FROM Categories");
        $categories = $categories_stmt->fetchAll(PDO::FETCH_ASSOC);

        $vendors_stmt = $pdo->query("SELECT * FROM Vendors");
        $vendors = $vendors_stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
} else {
    die("Product ID is required.");
}

// Handle form submission to update product
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    $category_id = $_POST['category_id'];
    $vendor_id = $_POST['vendor_id'];
    $image_url = $_POST['image_url'];

    try {
        // Update product details in the database
        $update_stmt = $pdo->prepare("UPDATE Products 
                                      SET name = ?, description = ?, price = ?, stock_quantity = ?, 
                                          category_id = ?, vendor_id = ?, image_url = ? 
                                      WHERE product_id = ?");
        $update_stmt->execute([$name, $description, $price, $stock_quantity, $category_id, $vendor_id, $image_url, $product_id]);

        echo "<p>Product updated successfully!</p>";
        echo "<a href='admin_products.php'>Back to Product List</a>";
        exit;
    } catch (PDOException $e) {
        die("Update failed: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Edit Product</h2>
    <form action="edit_product.php?id=<?= $product['product_id'] ?>" method="POST">
        <label for="name">Product Name:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($product['name']) ?>" required><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?= htmlspecialchars($product['description']) ?></textarea><br>

        <label for="price">Price ($):</label>
        <input type="number" id="price" name="price" value="<?= htmlspecialchars($product['price']) ?>" required><br>

        <label for="stock_quantity">Stock Quantity:</label>
        <input type="number" id="stock_quantity" name="stock_quantity" value="<?= htmlspecialchars($product['stock_quantity']) ?>" required><br>

        <label for="category_id">Category:</label>
        <select id="category_id" name="category_id" required>
            <?php foreach ($categories as $category): ?>
                <option value="<?= $category['category_id'] ?>" <?= $category['category_id'] == $product['category_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($category['category_name']) ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <label for="vendor_id">Vendor:</label>
        <select id="vendor_id" name="vendor_id" required>
            <?php foreach ($vendors as $vendor): ?>
                <option value="<?= $vendor['vendor_id'] ?>" <?= $vendor['vendor_id'] == $product['vendor_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($vendor['vendor_name']) ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <label for="image_url">Image URL:</label>
        <input type="text" id="image_url" name="image_url" value="<?= htmlspecialchars($product['image_url']) ?>"><br>

        <button type="submit">Update Product</button>
    </form>

    <br>
    <a href="admin.php?page=products">Back to Product List</a>
</body>
</html>
