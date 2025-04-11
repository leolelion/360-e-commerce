<?php
require_once 'config.php';
require_once 'classes/ActivityTracker.php';
session_start();

$tracker = new App\ActivityTracker($pdo);

$tracker->logPageView('cart');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_quantity'])) {
        $product_id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
        $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
        
        if ($product_id && $quantity && $quantity > 0) {
            $stmt = $pdo->prepare("UPDATE Cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$quantity, $_SESSION['user_id'], $product_id]);
            
            $tracker->logCartAction('update_quantity', $product_id, $quantity);
        }
    } elseif (isset($_POST['remove_item'])) {
        $product_id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
        
        if ($product_id) {
            $stmt = $pdo->prepare("DELETE FROM Cart WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$_SESSION['user_id'], $product_id]);
            
            $tracker->logCartAction('remove_from_cart', $product_id);
        }
    }
    
    header("Location: cart.php");
    exit;
}

$cart_items = [];
$subtotal = 0;

try {
    $stmt = $pdo->prepare("
        SELECT c.*, p.name, p.price, p.image_url, p.description, p.stock_quantity
        FROM Cart c
        JOIN Products p ON c.product_id = p.product_id
        WHERE c.user_id = ?
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($cart_items as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
} catch (PDOException $e) {
    $error = "Error loading cart: " . $e->getMessage();
}

$discount = $subtotal * 0.20; //set discount
$delivery_fee = 10.00;
$total = $subtotal - $discount + $delivery_fee;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - SHOP.CO</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/cart.css">
    <style>
        .stock-info {
    color: <?= ($item['stock_quantity'] > 0) ? 'green' : 'red' ?>;
    font-weight: bold;
}
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <main>
        <h1>YOUR CART</h1>
        
        <?php if (isset($error)): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <div class="cart-container">
            <div class="cart-items">
                <?php if (empty($cart_items)): ?>
                    <div class="empty-cart">
                        <p>Your cart is empty</p>
                        <a href="browse.php" class="continue-shopping">Continue Shopping</a>
                    </div>
                <?php else: ?>
                    <?php foreach ($cart_items as $item): ?>
                        <div class="cart-item">
                            <img src="<?= htmlspecialchars('../assets/images/' . basename($item['image_url'])) ?>" 
                                 alt="<?= htmlspecialchars($item['name']) ?>">
                            <div class="item-details">
                                <h2><?= htmlspecialchars($item['name']) ?></h2>
                                <p class="description"><?= htmlspecialchars($item['description']) ?></p>
                                <p class="stock-info">
                                    <?= ($item['stock_quantity'] > 0) ? 'In Stock' : 'Out of Stock' ?>
                                </p>
                                <p class="price">$<?= number_format($item['price'], 2) ?></p>
                                
                                <form method="post" class="quantity-form">
                                    <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                                    <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" class="quantity-input">
                                    <button type="submit" name="update_quantity" class="update-btn">Update</button>
                                </form>
                                
                                <form method="post" class="remove-form">
                                    <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                                    <button type="submit" name="remove_item" class="remove-btn">Remove</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="order-summary">
                <h2>Order Summary</h2>
                <p>Subtotal $<?= number_format($subtotal, 2) ?></p>
                <p>Discount (-20%) -$<?= number_format($discount, 2) ?></p>
                <p>Delivery Fee $<?= number_format($delivery_fee, 2) ?></p>
                <p><strong>Total $<?= number_format($total, 2) ?></strong></p>
                
                <div class="promo-code">
                    <input type="text" placeholder="Add promo code">
                    <button>Apply</button>
                </div>
                
                <a href="checkout.php" class="checkout-button" <?= empty($cart_items) ? 'style="pointer-events:none;opacity:0.5;"' : '' ?>>
                    Go to Checkout →
                </a>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>