<?php
session_start();
require_once 'config.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'dashboard';

$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d', strtotime('-30 days'));
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

try {
    if ($active_tab === 'dashboard') {
        $users_stmt = $pdo->query("SELECT COUNT(*) as total FROM Users");
        $total_users = $users_stmt->fetch(PDO::FETCH_ASSOC)['total'];

        $activity_stmt = $pdo->prepare("
            SELECT DATE(created_at) as date, COUNT(*) as count 
            FROM UserActivity 
            WHERE created_at BETWEEN ? AND ? 
            GROUP BY DATE(created_at)
        ");
        $activity_stmt->execute([$start_date, $end_date]);
        $activity_data = $activity_stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get sales data
        $sales_stmt = $pdo->prepare("
            SELECT DATE(created_at) as date, SUM(total_price) as total 
            FROM Orders 
            WHERE created_at BETWEEN ? AND ? 
            AND order_status != 'Cancelled'
            GROUP BY DATE(created_at)
        ");
        $sales_stmt->execute([$start_date, $end_date]);
        $sales_data = $sales_stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get popular products
        $products_stmt = $pdo->prepare("
            SELECT p.name, COUNT(oi.order_item_id) as order_count 
            FROM OrderItems oi 
            JOIN Products p ON oi.product_id = p.product_id 
            JOIN Orders o ON oi.order_id = o.order_id
            WHERE o.created_at BETWEEN ? AND ? 
            AND o.order_status != 'Cancelled'
            GROUP BY p.product_id 
            ORDER BY order_count DESC 
            LIMIT 5
        ");
        $products_stmt->execute([$start_date, $end_date]);
        $popular_products = $products_stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get total orders count
        $orders_stmt = $pdo->prepare("
            SELECT COUNT(*) as total 
            FROM Orders 
            WHERE created_at BETWEEN ? AND ? 
            AND order_status != 'Cancelled'
        ");
        $orders_stmt->execute([$start_date, $end_date]);
        $total_orders = $orders_stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Get total revenue
        $revenue_stmt = $pdo->prepare("
            SELECT SUM(total_price) as total 
            FROM Orders 
            WHERE created_at BETWEEN ? AND ? 
            AND order_status != 'Cancelled'
        ");
        $revenue_stmt->execute([$start_date, $end_date]);
        $total_revenue = $revenue_stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        // Get active users count
        $active_users_stmt = $pdo->prepare("
            SELECT COUNT(DISTINCT user_id) as total 
            FROM UserActivity 
            WHERE created_at BETWEEN ? AND ?
        ");
        $active_users_stmt->execute([$start_date, $end_date]);
        $active_users = $active_users_stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Get activity tracking data
        $activity_types = ['page_view', 'product_view', 'add_to_cart', 'purchase'];
        $activity_data = [];
        
        foreach ($activity_types as $type) {
            $stmt = $pdo->prepare("
                SELECT DATE(created_at) as date, COUNT(*) as count 
                FROM UserActivity 
                WHERE activity_type = ? 
                AND created_at BETWEEN ? AND ? 
                GROUP BY DATE(created_at)
            ");
            $stmt->execute([$type, $start_date, $end_date]);
            $activity_data[$type] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // Get top viewed products
        $top_products_stmt = $pdo->prepare("
            SELECT 
                JSON_EXTRACT(activity_details, '$.product_name') as product_name,
                COUNT(*) as view_count
            FROM UserActivity 
            WHERE activity_type = 'product_view'
            AND created_at BETWEEN ? AND ?
            GROUP BY JSON_EXTRACT(activity_details, '$.product_name')
            ORDER BY view_count DESC
            LIMIT 5
        ");
        $top_products_stmt->execute([$start_date, $end_date]);
        $top_products = $top_products_stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get user engagement stats
        $engagement_stmt = $pdo->prepare("
            SELECT 
                COUNT(DISTINCT user_id) as total_users,
                COUNT(DISTINCT CASE WHEN is_anonymous = 0 THEN user_id END) as registered_users,
                COUNT(DISTINCT CASE WHEN is_anonymous = 1 THEN ip_address END) as anonymous_users,
                COUNT(*) as total_activities
            FROM UserActivity 
            WHERE created_at BETWEEN ? AND ?
        ");
        $engagement_stmt->execute([$start_date, $end_date]);
        $engagement_stats = $engagement_stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Orders data
    if ($active_tab === 'orders') {
        $orders_list_stmt = $pdo->query("
            SELECT o.order_id, o.total_price, o.order_status, o.created_at, u.first_name, u.last_name 
            FROM Orders o
            JOIN Users u ON o.user_id = u.user_id
            ORDER BY o.created_at DESC
        ");
        $orders_list = $orders_list_stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Users data
    if ($active_tab === 'users') {
        // Get filter parameters
        $name = isset($_GET['name']) ? $_GET['name'] : '';
        $email = isset($_GET['email']) ? $_GET['email'] : '';
        $role = isset($_GET['role']) ? $_GET['role'] : '';

        // Build query
        $query = "SELECT * FROM Users WHERE 1";

        if ($name) {
            $query .= " AND (first_name LIKE :name OR last_name LIKE :name)";
        }
        if ($email) {
            $query .= " AND email LIKE :email";
        }
        if ($role) {
            $query .= " AND role = :role";
        }

        try {
            $stmt = $pdo->prepare($query);

            if ($name) {
                $stmt->bindValue(':name', '%' . $name . '%');
            }
            if ($email) {
                $stmt->bindValue(':email', '%' . $email . '%');
            }
            if ($role) {
                $stmt->bindValue(':role', $role);
            }

            $stmt->execute();
            $users_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }
    }

    // Products data
    if ($active_tab === 'products') {
        // Get categories and vendors for filters
        $categories_stmt = $pdo->query("SELECT * FROM Categories");
        $categories = $categories_stmt->fetchAll(PDO::FETCH_ASSOC);

        $vendors_stmt = $pdo->query("SELECT * FROM Vendors");
        $vendors = $vendors_stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get filter parameters
        $name = isset($_GET['name']) ? $_GET['name'] : '';
        $category_id = isset($_GET['category_id']) ? $_GET['category_id'] : '';
        $vendor_id = isset($_GET['vendor_id']) ? $_GET['vendor_id'] : '';

        // Build query
        $query = "SELECT p.product_id, p.name, p.description, p.price, p.stock_quantity, p.image_url, 
                         c.category_name, v.vendor_name 
                  FROM Products p 
                  JOIN Categories c ON p.category_id = c.category_id
                  JOIN Vendors v ON p.vendor_id = v.vendor_id
                  WHERE 1";

        if ($name) {
            $query .= " AND p.name LIKE :name";
        }
        if ($category_id) {
            $query .= " AND p.category_id = :category_id";
        }
        if ($vendor_id) {
            $query .= " AND p.vendor_id = :vendor_id";
        }

        try {
            $stmt = $pdo->prepare($query);

            if ($name) {
                $stmt->bindValue(':name', '%' . $name . '%');
            }
            if ($category_id) {
                $stmt->bindValue(':category_id', $category_id);
            }
            if ($vendor_id) {
                $stmt->bindValue(':vendor_id', $vendor_id);
            }

            $stmt->execute();
            $products_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }
    }

} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - ShopCo</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Base styles */
        body {
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Tab styles */
        .tabs {
            display: flex;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .tab {
            padding: 15px 25px;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
        }

        .tab:hover {
            background-color: #f0f0f0;
        }

        .tab.active {
            border-bottom: 3px solid #4CAF50;
            color: #4CAF50;
            font-weight: bold;
        }

        /* Dashboard styles */
        .dashboard-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            padding: 20px;
        }

        .card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .filters {
            grid-column: 1 / -1;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }

        .stat-card h3 {
            margin: 0;
            color: #666;
        }

        .stat-card .value {
            font-size: 24px;
            font-weight: bold;
            margin: 10px 0;
        }

        /* Table styles */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .data-table th,
        .data-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .data-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .data-table tr:hover {
            background-color: #f5f5f5;
        }

        .data-table .actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-primary {
            background-color: #4CAF50;
            color: white;
        }

        .btn-danger {
            background-color: #f44336;
            color: white;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .filters form {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .filters button {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .filters button:hover {
            background-color: #45a049;
        }

        /* Status badges */
        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-pending {
            background-color: #ffc107;
            color: #000;
        }

        .status-completed {
            background-color: #4CAF50;
            color: white;
        }

        .status-cancelled {
            background-color: #f44336;
            color: white;
        }

        .tracking-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        .tracking-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .tracking-card h3 {
            margin: 0;
            color: #666;
            font-size: 16px;
        }

        .tracking-card .value {
            font-size: 24px;
            font-weight: bold;
            margin: 10px 0;
        }

        .tracking-card .label {
            color: #999;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="dashboard-header">
        <a href="index.php" class="btn btn-primary">Return to Home</a>
    </div>

    <main>
        <div class="container">
            <div class="tabs">
                <a href="?tab=dashboard" class="tab <?= $active_tab === 'dashboard' ? 'active' : '' ?>">Dashboard</a>
                <a href="?tab=orders" class="tab <?= $active_tab === 'orders' ? 'active' : '' ?>">Orders</a>
                <a href="?tab=users" class="tab <?= $active_tab === 'users' ? 'active' : '' ?>">Users</a>
                <a href="?tab=products" class="tab <?= $active_tab === 'products' ? 'active' : '' ?>">Products</a>
            </div>

            <?php if ($active_tab === 'dashboard'): ?>
                <div class="filters">
                    <h2>Admin Dashboard</h2>
                    <form method="GET" action="">
                        <input type="hidden" name="tab" value="dashboard">
                        <label for="start_date">Start Date:</label>
                        <input type="date" name="start_date" value="<?= htmlspecialchars($start_date) ?>">
                        
                        <label for="end_date">End Date:</label>
                        <input type="date" name="end_date" value="<?= htmlspecialchars($end_date) ?>">
                        
                        <button type="submit">Apply Filters</button>
                    </form>
                </div>

                <div class="stats-grid">
                    <div class="stat-card">
                        <h3>Total Users</h3>
                        <div class="value"><?= $total_users ?></div>
                    </div>
                    <div class="stat-card">
                        <h3>Total Orders</h3>
                        <div class="value"><?= $total_orders ?></div>
                    </div>
                    <div class="stat-card">
                        <h3>Total Revenue</h3>
                        <div class="value">$<?= number_format($total_revenue, 2) ?></div>
                    </div>
                    <div class="stat-card">
                        <h3>Active Users</h3>
                        <div class="value"><?= $active_users ?></div>
                    </div>
                </div>

                <div class="tracking-stats">
                    <div class="tracking-card">
                        <h3>Total Activities</h3>
                        <div class="value"><?= number_format($engagement_stats['total_activities']) ?></div>
                        <div class="label">Last 30 days</div>
                    </div>
                    <div class="tracking-card">
                        <h3>Registered Users</h3>
                        <div class="value"><?= number_format($engagement_stats['registered_users']) ?></div>
                        <div class="label">Active users</div>
                    </div>
                    <div class="tracking-card">
                        <h3>Anonymous Users</h3>
                        <div class="value"><?= number_format($engagement_stats['anonymous_users']) ?></div>
                        <div class="label">Unique visitors</div>
                    </div>
                    <div class="tracking-card">
                        <h3>Total Users</h3>
                        <div class="value"><?= number_format($engagement_stats['total_users']) ?></div>
                        <div class="label">All time</div>
                    </div>
                </div>

                <div class="dashboard-container">
                    <div class="card">
                        <h3>User Activity</h3>
                        <div class="chart-container">
                            <canvas id="activityChart"></canvas>
                        </div>
                    </div>

                    <div class="card">
                        <h3>Sales Overview</h3>
                        <div class="chart-container">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>

                    <div class="card">
                        <h3>Popular Products</h3>
                        <div class="chart-container">
                            <canvas id="productsChart"></canvas>
                        </div>
                    </div>

                    <div class="card">
                        <h3>User Growth</h3>
                        <div class="chart-container">
                            <canvas id="growthChart"></canvas>
                        </div>
                    </div>

                    <div class="card">
                        <h3>User Activity Types</h3>
                        <div class="chart-container">
                            <canvas id="activityTypesChart"></canvas>
                        </div>
                    </div>

                    <div class="card">
                        <h3>Top Viewed Products</h3>
                        <div class="chart-container">
                            <canvas id="topProductsChart"></canvas>
                        </div>
                    </div>
                </div>
            <?php elseif ($active_tab === 'orders'): ?>
                <div class="card">
                    <h2>Orders Management</h2>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>User</th>
                                <th>Total Price</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders_list as $order): ?>
                                <tr>
                                    <td><?= htmlspecialchars($order['order_id']) ?></td>
                                    <td><?= htmlspecialchars($order['first_name']) ?> <?= htmlspecialchars($order['last_name']) ?></td>
                                    <td>$<?= number_format($order['total_price'], 2) ?></td>
                                    <td>
                                        <span class="status-badge status-<?= strtolower($order['order_status']) ?>">
                                            <?= htmlspecialchars($order['order_status']) ?>
                                        </span>
                                    </td>
                                    <td><?= date("Y-m-d H:i:s", strtotime($order['created_at'])) ?></td>
                                    <td class="actions">
                                        <a href="view_order.php?id=<?= $order['order_id'] ?>" class="btn btn-primary">View</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php elseif ($active_tab === 'users'): ?>
                <div class="card">
                    <h2>Users Management</h2>
                    <div style="margin-bottom: 20px;">
                        <a href="add_user.php" class="btn btn-primary">Add New User</a>
                    </div>

                    <form method="GET" action="" class="filters">
                        <input type="hidden" name="tab" value="users">
                        <input type="text" name="name" placeholder="Search by name" 
                               value="<?= isset($_GET['name']) ? htmlspecialchars($_GET['name']) : '' ?>"
                               class="form-control">
                        
                        <input type="text" name="email" placeholder="Search by email" 
                               value="<?= isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '' ?>"
                               class="form-control">

                        <select name="role" class="form-control">
                            <option value="">Select Role</option>
                            <option value="admin" <?= isset($_GET['role']) && $_GET['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                            <option value="user" <?= isset($_GET['role']) && $_GET['role'] === 'user' ? 'selected' : '' ?>>User</option>
                            <option value="vendor" <?= isset($_GET['role']) && $_GET['role'] === 'vendor' ? 'selected' : '' ?>>Vendor</option>
                        </select>

                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="?tab=users" class="btn btn-secondary">Clear Filters</a>
                    </form>

                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($users_list)): ?>
                                <tr>
                                    <td colspan="8" style="text-align: center;">No users found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($users_list as $user): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($user['user_id']) ?></td>
                                        <td><?= htmlspecialchars($user['first_name']) ?></td>
                                        <td><?= htmlspecialchars($user['last_name']) ?></td>
                                        <td><?= htmlspecialchars($user['email']) ?></td>
                                        <td><?= htmlspecialchars($user['role']) ?></td>
                                        <td><?= htmlspecialchars($user['phone']) ?></td>
                                        <td><?= htmlspecialchars($user['address']) ?></td>
                                        <td class="actions">
                                            <a href="edit_user.php?id=<?= $user['user_id'] ?>" class="btn btn-primary">Edit</a>
                                            <a href="delete_user.php?id=<?= $user['user_id'] ?>" 
                                               class="btn btn-danger" 
                                               onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php elseif ($active_tab === 'products'): ?>
                <div class="card">
                    <h2>Products Management</h2>
                    <div style="margin-bottom: 20px;">
                        <a href="add_product.php" class="btn btn-primary">Add New Product</a>
                    </div>

                    <form method="GET" action="" class="filters">
                        <input type="hidden" name="tab" value="products">
                        <input type="text" name="name" placeholder="Search by name" 
                               value="<?= isset($_GET['name']) ? htmlspecialchars($_GET['name']) : '' ?>"
                               class="form-control">
                        
                        <select name="category_id" class="form-control">
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['category_id'] ?>" 
                                        <?= isset($_GET['category_id']) && $_GET['category_id'] == $category['category_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category['category_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <select name="vendor_id" class="form-control">
                            <option value="">Select Vendor</option>
                            <?php foreach ($vendors as $vendor): ?>
                                <option value="<?= $vendor['vendor_id'] ?>" 
                                        <?= isset($_GET['vendor_id']) && $_GET['vendor_id'] == $vendor['vendor_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($vendor['vendor_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="?tab=products" class="btn btn-secondary">Clear Filters</a>
                    </form>

                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Product ID</th>
                                <th>Vendor</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Category</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($products_list)): ?>
                                <tr>
                                    <td colspan="9" style="text-align: center;">No products found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($products_list as $product): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($product['product_id']) ?></td>
                                        <td><?= htmlspecialchars($product['vendor_name']) ?></td>
                                        <td>
                                            <?php if ($product['image_url']): ?>
                                                <img src="<?= htmlspecialchars($product['image_url']) ?>" 
                                                     alt="Product Image" 
                                                     style="max-width: 50px; max-height: 50px; object-fit: cover;">
                                            <?php else: ?>
                                                No Image
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($product['name']) ?></td>
                                        <td><?= htmlspecialchars($product['description']) ?></td>
                                        <td>$<?= number_format($product['price'], 2) ?></td>
                                        <td><?= htmlspecialchars($product['stock_quantity']) ?></td>
                                        <td><?= htmlspecialchars($product['category_name']) ?></td>
                                        <td class="actions">
                                            <a href="edit_product.php?id=<?= $product['product_id'] ?>" 
                                               class="btn btn-primary">Edit</a>
                                            <a href="delete_product.php?id=<?= $product['product_id'] ?>" 
                                               class="btn btn-danger"
                                               onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php if ($active_tab === 'dashboard'): ?>
    <script>
        // User Activity Chart
        new Chart(document.getElementById('activityChart'), {
            type: 'line',
            data: {
                labels: <?= json_encode(array_column($activity_data, 'date')) ?>,
                datasets: [{
                    label: 'User Activity',
                    data: <?= json_encode(array_column($activity_data, 'count')) ?>,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Sales Chart
        new Chart(document.getElementById('salesChart'), {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_column($sales_data, 'date')) ?>,
                datasets: [{
                    label: 'Sales',
                    data: <?= json_encode(array_column($sales_data, 'total')) ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Popular Products Chart
        new Chart(document.getElementById('productsChart'), {
            type: 'doughnut',
            data: {
                labels: <?= json_encode(array_column($popular_products, 'name')) ?>,
                datasets: [{
                    data: <?= json_encode(array_column($popular_products, 'order_count')) ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 206, 86, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(153, 102, 255, 0.5)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // User Growth Chart
        new Chart(document.getElementById('growthChart'), {
            type: 'line',
            data: {
                labels: <?= json_encode(array_column($activity_data, 'date')) ?>,
                datasets: [{
                    label: 'User Growth',
                    data: <?= json_encode(array_column($activity_data, 'count')) ?>,
                    borderColor: 'rgb(255, 99, 132)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Activity Types Chart
        new Chart(document.getElementById('activityTypesChart'), {
            type: 'bar',
            data: {
                labels: <?= json_encode($activity_types) ?>,
                datasets: [{
                    label: 'Activity Count',
                    data: <?= json_encode(array_map(function($type) use ($activity_data) {
                        return array_sum(array_column($activity_data[$type], 'count'));
                    }, $activity_types)) ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 206, 86, 0.5)',
                        'rgba(75, 192, 192, 0.5)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Top Products Chart
        new Chart(document.getElementById('topProductsChart'), {
            type: 'doughnut',
            data: {
                labels: <?= json_encode(array_column($top_products, 'product_name')) ?>,
                datasets: [{
                    data: <?= json_encode(array_column($top_products, 'view_count')) ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 206, 86, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(153, 102, 255, 0.5)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    </script>
    <?php endif; ?>
</body>
</html>