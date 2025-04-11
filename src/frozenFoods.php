<?php
require_once 'config.php';

// Hardcoded 8 produce product IDs
$productIds = [36, 37, 38, 39, 40, 41, 42, 43];


$placeholders = implode(',', array_fill(0, count($productIds), '?'));


$stmt = $pdo->prepare("SELECT product_id, name, price, image_url, description FROM Products WHERE product_id IN ($placeholders)");
$stmt->execute($productIds);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$productMap = [];
foreach ($products as $product) {
    $productMap[$product['product_id']] = $product;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Produce - SHOP.CO</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/styles.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/category.css">
</head>
<body>
<?php include BASE_PATH . 'header.php'; ?>

<main>
    <h2>Frozen Foods</h2>
    <div class="product-grid">
        <?php foreach ($productIds as $id): ?>
            <?php if (isset($productMap[$id])): 
                $product = $productMap[$id]; ?>
                <a href="product.php?id=<?= $product['product_id'] ?>" class="product">
                    <img src="<?= BASE_URL ?>assets/images/<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <p><?= htmlspecialchars($product['description']) ?></p>
                    <p>$<?= number_format($product['price'], 2) ?></p>
                </a>
            <?php else: ?>
                <div class="product missing">
                    <p>Product ID <?= $id ?> not found.</p>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</main>

<?php include BASE_PATH . 'footer.php'; ?>
</body>
</html>