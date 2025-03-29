<?php
// session_start();
// if (!isset($_SESSION['admin_logged_in'])) {
//     header("Location: login.php");
//     exit();
// }

include('config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css"> <!-- Custom CSS -->
</head>
<body>
    <div class="admin-container">
        <h2>Admin Dashboard</h2>
        <nav>
            <ul>
                <li><a href="admin.php?page=dashboard">Dashboard</a></li>
                <li><a href="admin.php?page=products">Manage Products</a></li>
                <li><a href="admin.php?page=orders">Orders</a></li>
                <li><a href="admin.php?page=users">Users</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>

        <div class="content">
        <?php
            session_start();
            include('config/db.php');
            include('includes/header.php');
            include('includes/sidebar.php');

            $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

            if (file_exists("pages/admin_$page.php")) {
                include("pages/admin_$page.php");
            } else {
                echo "<h3>Page not found</h3>";
            }

            include('includes/footer.php');
            ?>

            ?>
        </div>
    </div>
</body>
</html>