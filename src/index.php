<?php
session_start();
require_once 'config.php';

// Fetch HOT PRODUCTS (4 random items)
$hotStmt = $pdo->prepare("SELECT * FROM Products ORDER BY RAND() LIMIT 4");
$hotStmt->execute();
$hotProducts = $hotStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch MORE PRODUCTS (next 4 newest)
$moreStmt = $pdo->prepare("SELECT * FROM Products ORDER BY product_id DESC LIMIT 4 OFFSET 4");
$moreStmt->execute();
$moreProducts = $moreStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/index.css">
</head>
<body>
<?php include 'header.php'; ?>

<main>
    <section class="categories">
        <h2>Categories</h2>
        <div class="category-list">
            <a href="produce.php?name=produce" class="category">
                <img src="<?= BASE_URL ?>assets/images/fruitsVegetables.png" alt="Fruits & Vegetables">
                <span>Produce</span>
            </a>
            <a href="meats.php?name=meats" class="category">
                <img src="<?= BASE_URL ?>assets/images/meats.png" alt="Meat & Seafood">
                <span>Meats</span>
            </a>
            <a href="dairy.php?name=dairy" class="category">
                <img src="<?= BASE_URL ?>assets/images/dairy.png" alt="Dairy Products">
                <span>Dairy</span>
            </a>
            <a href="frozenFoods.php?name=frozen%20foods" class="category">
                <img src="<?= BASE_URL ?>assets/images/frozenFoods.png" alt="Frozen Foods">
                <span>Frozen Foods</span>
            </a>
            <a href="pantryFoods.php?name=pantry%20foods" class="category">
                <img src="<?= BASE_URL ?>assets/images/pantryFoods.png" alt="Pantry Foods">
                <span>Pantry Foods</span>
            </a>
            <a href="snacks.php?name=snacks" class="category">
                <img src="<?= BASE_URL ?>assets/images/snacksCandy.png" alt="Snacks & Candy">
                <span>Snacks</span>
            </a>
            <a href="freshFoods.php?name=fresh%20foods" class="category">
                <img src="<?= BASE_URL ?>assets/images/freshFoods.png" alt="Deli & Fresh Prepared Meals">
                <span>Fresh Foods</span>
            </a>
            <a href="alcohol.php?name=alcohol" class="category">
                <img src="<?= BASE_URL ?>assets/images/alcohol.png" alt="Alcohol Beverages">
                <span>Alcohol</span>
            </a>
            <a href="beverages.php?name=beverages" class="category">
                <img src="<?= BASE_URL ?>assets/images/drinks.png" alt="Beverages">
                <span>Beverages</span>
            </a>
            <a href="bakery.php?name=bakery" class="category">
                <img src="<?= BASE_URL ?>assets/images/bread.png" alt="Bakery Goods">
                <span>Bakery</span>
            </a>
            <a href="household.php?name=household" class="category">
                <img src="<?= BASE_URL ?>assets/images/laundry.png" alt="Household Items">
                <span>Household</span>
            </a>
            <a href="paperware.php?name=paperware" class="category">
                <img src="<?= BASE_URL ?>assets/images/tissue.png" alt="Paper Products">
                <span>Paperware</span>
            </a>
        </div>
    </section>

    <section class="hot-products">
        <h2>HOT PRODUCTS</h2>
        <div class="product-grid">
            <?php foreach ($hotProducts as $product): ?>
                <a href="product.php?id=<?= $product['product_id'] ?>" class="product">
                    <img src="<?= BASE_URL ?>assets/images/<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <p><?= htmlspecialchars($product['description']) ?></p>
                    <p>$<?= number_format($product['price'], 2) ?></p>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="all-products">
        <h2>MORE PRODUCTS</h2>
        <div class="product-grid">
            <?php foreach ($moreProducts as $product): ?>
                <a href="product.php?id=<?= $product['product_id'] ?>" class="product">
                    <img src="<?= BASE_URL ?>assets/images/<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <p><?= htmlspecialchars($product['description']) ?></p>
                    <p>$<?= number_format($product['price'], 2) ?></p>
                </a>
            <?php endforeach; ?>
        </div>
        <div class="pagination">
            <button>«</button>
            <button class="active">1</button>
            <button>2</button>
            <button>3</button>
            <button>»</button>
        </div>
    </section>
</main>

<?php include BASE_PATH . 'footer.php'; ?>
<script src="search.js"></script>
</body>
</html>