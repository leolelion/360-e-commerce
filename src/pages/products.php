<?php
include 'config.php';

$categories_stmt = $pdo->query("SELECT * FROM Categories");
$categories = $categories_stmt->fetchAll(PDO::FETCH_ASSOC);

$vendors_stmt = $pdo->query("SELECT * FROM Vendors");
$vendors = $vendors_stmt->fetchAll(PDO::FETCH_ASSOC);

$name = isset($_GET['name']) ? $_GET['name'] : '';
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : '';
$vendor_id = isset($_GET['vendor_id']) ? $_GET['vendor_id'] : '';

$query = "SELECT p.product_id, p.name, p.description, p.price, p.stock_quantity, p.image_url, 
                 c.category_name, v.vendor_name 
          FROM Products p 
          JOIN Categories c ON p.category_id = c.category_id
          JOIN Vendors v ON p.vendor_id = v.vendor_id
          WHERE 1";

if ($name) {
    $query .= " AND p.name LIKE :name";
}
if ($category_id) {
    $query .= " AND p.category_id = :category_id";
}
if ($vendor_id) {
    $query .= " AND p.vendor_id = :vendor_id";
}

try {
    $stmt = $pdo->prepare($query);

    if ($name) {
        $stmt->bindValue(':name', '%' . $name . '%');
    }
    if ($category_id) {
        $stmt->bindValue(':category_id', $category_id);
    }
    if ($vendor_id) {
        $stmt->bindValue(':vendor_id', $vendor_id);
    }

    $stmt->execute();

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
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Admin - Manage Products</h2>
    <a href="add_product.php">➕ Add New Product</a>

    <form method="GET" action="admin.php">
    <input type="hidden" name="page" value="products">
    <input type="text" name="name" placeholder="Search by name" value="<?= isset($_GET['name']) ? htmlspecialchars($_GET['name']) : '' ?>">
    <select name="category_id">
        <option value="">Select Category</option>
        <?php
        $categoriesStmt = $pdo->query("SELECT * FROM Categories");
        $categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($categories as $category) {
            echo '<option value="' . $category['category_id'] . '"' . (isset($_GET['category_id']) && $_GET['category_id'] == $category['category_id'] ? ' selected' : '') . '>' . htmlspecialchars($category['category_name']) . '</option>';
        }
        ?>
    </select>
    <select name="vendor_id">
        <option value="">Select Vendor</option>
        <?php
        $vendorsStmt = $pdo->query("SELECT * FROM Vendors");
        $vendors = $vendorsStmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($vendors as $vendor) {
            echo '<option value="' . $vendor['vendor_id'] . '"' . (isset($_GET['vendor_id']) && $_GET['vendor_id'] == $vendor['vendor_id'] ? ' selected' : '') . '>' . htmlspecialchars($vendor['vendor_name']) . '</option>';
        }
        ?>
    </select>
    <button type="submit">Search</button>
</form>


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
