<?php
session_start();
require_once 'config.php';

$selected_category = $_GET['category'] ?? '';
$min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
$max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : 1000;
$search_term = $_GET['search'] ?? '';

$query = "SELECT p.*, c.category_name FROM Products p
          JOIN Categories c ON p.category_id = c.category_id
          WHERE p.price BETWEEN ? AND ?";
$params = [$min_price, $max_price];
$types = "dd";

if (!empty($selected_category)) {
    $query .= " AND c.category_id = ?";
    $params[] = $selected_category;
    $types .= "i";
}

if (!empty($search_term)) {
    $query .= " AND (p.name LIKE ? OR p.description LIKE ?)";
    $params[] = "%$search_term%";
    $params[] = "%$search_term%";
    $types .= "ss";
}

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$categories = $conn->query("SELECT * FROM Categories")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Products - ShopCo</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/browse.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <main class="browse-container">
        <h1>Browse Products</h1>
        
        <section class="filters">
            <form method="GET" action="browse.php">
                <div class="filter-group">
                    <label for="category">Category:</label>
                    <select name="category" id="category">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['category_id'] ?>" 
                                <?= $selected_category == $category['category_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['category_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="price-range">Price Range:</label>
                    <div class="price-range">
                        <input type="number" name="min_price" min="0" step="0.01" placeholder="Min" 
                               value="<?= htmlspecialchars($min_price) ?>">
                        <span>to</span>
                        <input type="number" name="max_price" min="0" step="0.01" placeholder="Max" 
                               value="<?= htmlspecialchars($max_price) ?>">
                    </div>
                </div>
                
                <div class="filter-group">
                    <label for="search">Search:</label>
                    <input type="text" name="search" placeholder="Product name or description" 
                           value="<?= htmlspecialchars($search_term) ?>">
                </div>
                
                <button type="submit" class="filter-button">Apply Filters</button>
                <a href="browse.php" class="reset-button">Reset Filters</a>
            </form>
        </section>
        
        <section class="products">
            <div class="product-grid">
                <?php if ($products): ?>
                    <?php foreach ($products as $product): ?>
                        <div class="product-card">
                            <a href="product.php?id=<?= $product['product_id'] ?>" class="product-link">
                                <div class="product-image">
                                <img src="<?= htmlspecialchars('../assets/images/' . basename($product['image_url'])) ?>" 
                                    alt="<?= htmlspecialchars($product['name']) ?>">
                                </div>
                                <div class="product-info">
                                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                                </a>
                                <p class="category"><?= htmlspecialchars($product['category_name']) ?></p>
                                <p class="description"><?= htmlspecialchars($product['description']) ?></p>
                                <p class="price">$<?= number_format($product['price'], 2) ?></p>
                                <p class="stock"><?= $product['stock_quantity'] > 0 ? 'In Stock' : 'Out of Stock' ?></p>
                                <button class="add-to-cart" data-product-id="<?= $product['product_id'] ?>">
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-products">No products found matching your filters.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>
    
    <?php include 'footer.php'; ?>
    
    <script>
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', async function() {
            const productId = this.dataset.productId;
            
            try {
                const formData = new FormData();
                formData.append('product_id', productId);
                formData.append('quantity', 1);

                const response = await fetch('add_to_cart.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    updateCartCounter();
                    showToast(result.message || 'Item added to cart');
                } else {
                    showToast(result.message || 'Failed to add item', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Please login to shop :)', 'error');
            }
        });
    });

    function updateCartCounter() {
        fetch('get_cart_count.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const counter = document.querySelector('.cart-counter');
                    if (counter) counter.textContent = data.count;
                }
            });
    }

    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }
    </script>
</body>
</html>

