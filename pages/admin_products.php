<?php
include('config/db.php');

if (!$conn) {
    // Use dummy data if database is not connected
    $products = [
        ['id' => 1, 'name' => 'Blueberries', 'vendor' => 'Fruit Farms', 'price' => 7, 'stock' => 10, 'category' => 'Fruits'],
        ['id' => 2, 'name' => 'Eggs', 'price' => 9, 'stock' => 5, 'category' => 'Dairy and Eggs'],
        ['id' => 3, 'name' => 'Spinach', 'price' => 1.50, 'stock' => 20, 'category' => 'Produce'],
        ['id' => 4, 'name' => 'Bread', 'price' => 2.50, 'stock' => 15, 'category' => 'Bakery'],
        ['id' => 5, 'name' => 'Chicken', 'price' => 10, 'stock' => 8, 'category' => 'Meat'],
    ];
} else {
    // Fetch products from database
    $result = mysqli_query($conn, "SELECT * FROM products");
    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Products</title>
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/products.css">
</head>
<body>

    <div class="admin-container">
        <h2>Product Management</h2>

        <button onclick="window.location.href='add_product.php'" class="btn-add">Add Product</button>

        <table class="products-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Vendor</th>
                    <th>Price ($)</th>
                    <th>Stock</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= $product['id']; ?></td>
                    <td><?= $product['name']; ?></td>
                    <td><?= $product['vendor'] ?? '-'; ?></td>
                    <td><?= $product['price']; ?></td>
                    <td><?= $product['stock']; ?></td>
                    <td><?= $product['category']; ?></td>
                    <td>
                        <button onclick="editProduct(<?= $product['id']; ?>)" class="btn-edit">Edit</button>
                        <button onclick="deleteProduct(<?= $product['id']; ?>)" class="btn-delete">Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="js/products.js"></script>

</body>
</html>
<style>
    .admin-container {
    padding: 20px;
    background: #fff;
}

.btn-add {
    background: #28a745;
    color: white;
    padding: 10px;
    border: none;
    cursor: pointer;
    margin-bottom: 10px;
}

.products-table {
    width: 100%;
    border-collapse: collapse;
}

.products-table th, .products-table td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: center;
}

.products-table th {
    background: #f4f4f4;
}

.btn-edit {
    background: #ffc107;
    color: white;
    padding: 5px;
    border: none;
    cursor: pointer;
}

.btn-delete {
    background: #dc3545;
    color: white;
    padding: 5px;
    border: none;
    cursor: pointer;
}

</style>