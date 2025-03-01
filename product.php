<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Page - SHOP.CO</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>


<header>
        
        
        <div class="nav-container">
            <h1><a href="home.html">SHOP.CO</a></h1>
            <nav>
                <ul>
                    <li><a href="home.html">Shop</a></li>
                    <li><a href="login.html">Log in</a></li>
                    <li><a href="register.html">Register</a></li>
                </ul>
            </nav>
            <div class="search-bar"><input type="text" placeholder="Search for products..."></div>
            <div class="icons">
                <a href="cart.php">🛒</a>
                <a href="profile.php">👤</a>
            </div>
        </div>
    
</header>

    <!-- Main Product Section, placeholder items for presentation -->
    <main>
        <section class="product-container">
            <div class="product-image">
                <img src="images/blueberries.jpeg" alt="Blueberries (FRZ)">
            </div>
            <div class="product-details">
                <h2>Blueberries (FRZ)</h2>
                <p class="rating">⭐⭐⭐⭐ 4/5</p>
                <p class="price">$20 <span class="old-price">$30</span> <span class="discount">-40%</span></p>
                <p class="description">
                    Throw some in muffins, pancakes, waffles or a smoothie! One pound is enough for a pie.
                </p>
                <label>Choose Size:</label>
                <div class="size-options">
                    <button>1lb</button>
                    <button>2lb</button>
                    <button>5lb</button>
                    <button>25lb</button>
                </div>
                <div class="quantity">
                    <button>-</button>
                    <input type="number" value="1">
                    <button>+</button>
                </div>
                <button class="add-to-cart">Add to Cart</button>
            </div>
        </section>

        <section class="reviews">
    <h3>Customer Reviews</h3>
    <div class="review">
        <p><strong>John D.</strong> ⭐⭐⭐⭐⭐</p>
        <p>These blueberries are amazing! Super fresh and perfect for smoothies.</p>
    </div>
    <div class="review">
        <p><strong>Emily R.</strong> ⭐⭐⭐⭐☆</p>
        <p>Great taste, but I wish the packaging was a bit better.</p>
    </div>
    <div class="review">
        <p><strong>Michael S.</strong> ⭐⭐⭐⭐⭐</p>
        <p>Best frozen blueberries I've ever bought! Will buy again.</p>
    </div>
</section>
<section class="recommended">
    <h3>You Might Also Like</h3>
    <div class="products">
        <div class="product">
            <img src="images/raspberries.jpeg" alt="Raspberries">
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
