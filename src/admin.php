<?php
include('config.php');
include('header.php');


// Check if the user is logged in and if they have an admin role
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
//     header("Location: login.php");  // Redirect to login page if not an admin
//     exit();
// }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="admin-container">
        <h2>Admin Dashboard</h2>
        <nav>
            <ul>
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

            $page = isset($_GET['page']) ? $_GET['page'] : 'products';

            if (file_exists("pages/$page.php")) {
                include("pages/$page.php");
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