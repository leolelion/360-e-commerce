<?php
session_start();
require_once '../config.php';

// Check if user is logged in and is an admin
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
//     header('Location: login.php');
//     exit();
// }

// Get date range for filtering
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d', strtotime('-30 days'));
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

try {
    // Get total users count
    $users_stmt = $pdo->query("SELECT COUNT(*) as total FROM Users");
    $total_users = $users_stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Get user activity data
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

} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
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
        body {
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
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
    </style>
</head>
<body>
    <div class="filters">
        <h2>Admin Dashboard</h2>
        <form method="GET" action="">
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
    </div>

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

        // User Growth Chart (using the same data as activity for now)
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
    </script>
</body>
</html>