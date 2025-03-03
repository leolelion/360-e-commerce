<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Shop.co</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/profile.css">
    <script defer src="script.js"></script>
</head>
<body>
<?php include 'header.php'; ?>
    
    <main>
        <h1>Your Account</h1>
        
        <section class="account-info">
            <div>
                <label>Username</label>
                <input type="text" id="username" value="myusername">
                <button onclick="validateUsername()">Change Username</button>
                <p class="error" id="username-error"></p>
            </div>
            
            <div>
                <label>Password</label>
                <input type="password" id="password">
                <button onclick="validatePassword()">Change Password</button>
                <p class="error" id="password-error"></p>
            </div>
            
            <div>
                <label>Payment Information</label>
                <input type="text" id="payment" placeholder="Card Number">
                <button onclick="validatePayment()">Update Payment Information</button>
                <p class="error" id="payment-error"></p>
            </div>
        </section>

        <section class="orders">
            <h2>Past Orders</h2>
            <ul>
                <li>January 31, 2025 <button>View Receipt</button></li>
                <li>January 31, 2025 <button>View Receipt</button></li>
                <li>January 31, 2025 <button>View Receipt</button></li>
            </ul>
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
