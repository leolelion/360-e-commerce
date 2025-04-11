<header>
        
    <div class="nav-container">
        <div class="logo">
            <a href="index.php">
                <img src="../assets/images/logo.png" alt="ShopCo Logo" class="logo-image">
            </a>
        </div>
        <nav>
            <ul>
                <li><a href="browse.php">Shop</a></li>
                <li><a href="login.php">Log in</a></li>
                <li><a href="register.php">Register</a></li>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                            <li><a href="admin_dashboard.php" class="admin-tab">Admin Dashboard</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <div class="search-bar">
        <form action="browse.php" method="GET" class="header-search-form">
            <input type="text" name="search" placeholder="Search products..." required>
            <button type="submit">search</button>
        </form>

        </div>
        <div id="search-results"></div>
        <div class="icons">
            <a href="cart.php">🛒</a>
            <a href="profile_info.php">👤</a>
            <a href="profile.php">⛭</a>
        </div>
    </div>
<script src="../assets/jssearch.js" defer></script>
</header>