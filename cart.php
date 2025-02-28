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
            <h1>SHOP.CO</h1>
            <nav>
                <ul>
                    <li><a href="#">Shop</a></li>
                    <li><a href="#">On Sale</a></li>
                    <li><a href="#">New Arrivals</a></li>
                </ul>
            </nav>
            <div class="search-bar"><input type="text" placeholder="Search for products..."></div>
            <div class="icons">
                <a href="#">🛒</a>
                <a href="#">👤</a>
            </div>
        </div>
    </header>
    <main>
 <!-- placeholders for when we have actual stuff in DB -->
    <h1>YOUR CART</h1>
        <div class="cart-container">
            <div class="cart-items">
                <div class="cart-item">
                    <img src="images/blueberries.jpeg" alt="Baby Spinach">
                    <div class="item-details">
                        <h2>Baby Spinach</h2>
                        <p>Size: Large</p>
                        <p>Jayleaf Farms</p>
                        <p>$14.50</p>
                    </div>
                </div>
                <div class="cart-item">
                    <img src="images/blueberries.jpeg" alt="Raspberry">
                    <div class="item-details">
                        <h2>Raspberry</h2>
                        <p>Size: Medium</p>
                        <p>BC Berry Farms</p>
                        <p>$5</p>
                    </div>
                </div>
                <div class="cart-item">
                    <img src="images/blueberries.jpeg" alt="Large Eggs">
                    <div class="item-details">
                        <h2>Large Eggs</h2>
                        <p>Size: Large</p>
                        <p>Clemmore Farms</p>
                        <p>$8</p>
                    </div>
                </div>
            </div>


            <div class="order-summary">
                <h2>Order Summary</h2>
                <p>Subtotal $27.50</p>
                <p>Discount (-20%) -$3</p>
                <p>Delivery Fee $10</p>
                <p><strong>Total $34.50</strong></p>
                <div class="promo-code">
                    <input type="text" placeholder="Add promo code">
                    <button>Apply</button>
                </div>
                <button class="checkout-button">Go to Checkout →</button>
            </div>
        </div>
    </main>
    <script src="js/script.js"></script>
</body>
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