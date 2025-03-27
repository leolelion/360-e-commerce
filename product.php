<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Page - SHOP.CO</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/product.css">
</head>
<body>
<?php include 'header.php'; ?>

<?php
// Sample product data
$products = [
    "blueberries" => [
        "title" => "Blueberries (FRZ)",
        "image" => "images/blueberries.jpeg",
        "price" => "$20",
        "old_price" => "$30",
        "discount" => "-40%",
        "description" => "Throw some in muffins, pancakes, waffles or a smoothie! One pound is enough for a pie."
    ],
    "raspberries" => [
        "title" => "Raspberries (FRZ)",
        "image" => "images/rasberries.jpg",
        "price" => "$18",
        "old_price" => "$26",
        "discount" => "-31%",
        "description" => "Perfectly sweet and tart — ideal for parfaits, baking, or snacking frozen!"
    ],
    "eggs" => [
        "title" => "Large Eggs",
        "image" => "images/eggs2.png",
        "price" => "$6",
        "old_price" => "$8",
        "discount" => "-25%",
        "description" => "Farm-fresh large eggs, great for breakfast or baking. Comes in a dozen."
    ],
    "baby_spinach" => [
        "title" => "Baby Spinach",
        "image" => "images/babySpinach.png",
        "price" => "$4",
        "old_price" => "$6",
        "discount" => "-33%",
        "description" => "Crisp, fresh, and full of nutrients. Great raw or cooked!"
    ],
    "laundry-detergent" => [
    "title" => "Laundry Detergent",
    "image" => "images/laundryDetergent.png",
    "price" => "$15.97",
    "description" => "Powerful stain-fighting formula with a fresh scent. Safe for all machines."
    ],
    "oreo-minis" => [
    "title" => "Oreo Minis (Snack Pack)",
    "image" => "images/oreoMini.png",
    "price" => "$3.28",
    "description" => "Mini Oreos in a convenient snack-size pack. Perfect for on-the-go munching!"
    ],
    "strawberry-croissant" => [
    "title" => "Strawberry Cream Cheese Croissant",
    "image" => "images/strawberryCroissant.png",
    "price" => "$4.97",
    "description" => "Flaky croissant filled with sweet strawberry and creamy cheese — a bakery favorite!"
    ],
    "dr-pepper-zero" => [
    "title" => "Dr Pepper Zero (12 Pack)",
    "image" => "images/drPepper.png",
    "price" => "$7.78",
    "description" => "All the bold flavor of Dr Pepper with zero sugar. Refreshing and guilt-free!"
    ],
    
];

$item = $_GET['item'] ?? 'blueberries';
$product = $products[$item] ?? $products["blueberries"];
?>

<main>
    <section class="product-container">
        <div class="product-image">
            <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['title']; ?>">
        </div>
        <div class="product-details">
            <h2><?php echo $product['title']; ?></h2>
            <p class="rating">⭐⭐⭐⭐ 4/5</p>
            <p class="price"><?php echo $product['price']; ?> 
                <span class="old-price"><?php echo $product['old_price']; ?></span> 
                <span class="discount"><?php echo $product['discount']; ?></span>
            </p>
            <p class="description"><?php echo $product['description']; ?></p>

            <label for="quantity">Quantity:</label>
            <select id="quantity" name="quantity" class="quantity-dropdown">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
            <button class="add-to-cart">Add to Cart</button>
        </div>
    </section>

    <section class="reviews">
        <h3>Customer Reviews</h3>
        <div class="review">
            <p><strong>John D.</strong> ⭐⭐⭐⭐⭐</p>
            <p>These <?php echo $item; ?> are amazing! Super fresh and perfect for smoothies.</p>
        </div>
        <div class="review">
            <p><strong>Emily R.</strong> ⭐⭐⭐⭐☆</p>
            <p>Great taste, but I wish the packaging was a bit better.</p>
        </div>
        <div class="review">
            <p><strong>Michael S.</strong> ⭐⭐⭐⭐⭐</p>
            <p>Best <?php echo $item; ?> I've ever bought! Will buy again.</p>
        </div>
    </section>

    <section class="recommended">
        <h3>You Might Also Like</h3>
        <div class="products">
            <div class="product">
                <img src="images/rasberries.jpeg" alt="Raspberries">
                <p>Raspberries (FRZ) - $18</p>
            </div>
            <div class="product">
                <img src="images/strawberries.jpeg" alt="Strawberries">
                <p>Strawberries (FRZ) - $15</p>
            </div>
            <div class="product">
                <img src="images/mixed-berries.jpeg" alt="Mixed Berries">
                <p>Mixed Berries (FRZ) - $22</p>
            </div>
        </div>
    </section>
</main>


 
    <footer>
        <div class="footer-container">
            <div class="footer-logo">SHOP.CO</div>
            <p>info</p>
            <ul class="footer-links">
                <li><a href="#">About</a></li>
                <li><a href="#">Features</a></li>
                <li><a href="#">Works</a></li>
                <li><a href="#">Career</a></li>
            </ul>
            <ul class="footer-links">
                <li><a href="#">Customer Support</a></li>
                <li><a href="#">Delivery Details</a></li>
                <li><a href="#">Terms & Conditions</a></li>
                <li><a href="#">Privacy Policy</a></li>
            </ul>
            <ul class="footer-links">
                <li><a href="#">Free eBooks</a></li>
                <li><a href="#">Development Tutorial</a></li>
                <li><a href="#">How-to Blog</a></li>
                <li><a href="#">YouTube Playlist</a></li>
            </ul>
            <div class="payment-icons">VISA | PayPal | GPay | Apple Pay</div>
        </div>
    </footer>

</body>
</html>
