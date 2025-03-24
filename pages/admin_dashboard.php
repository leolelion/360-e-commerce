<?php
include('config.php');

// Dummy Data for Testing if Database is Not Connected
$use_dummy_data = !$pdo; // Check if $pdo is not set or false

$total_users = $use_dummy_data ? rand(50, 500) : 0;
$total_orders = $use_dummy_data ? rand(100, 1000) : 0;
$total_products = $use_dummy_data ? rand(200, 2000) : 0;
$total_revenue = $use_dummy_data ? rand(5000, 50000) + (rand(0, 99) / 100) : 0;

$recent_orders = $use_dummy_data ? [
    ["id" => rand(1000, 9999), "customer_name" => "John Doe", "total_price" => rand(50, 500), "status" => "Completed", "order_date" => "2025-02-28"],
    ["id" => rand(1000, 9999), "customer_name" => "Jane Smith", "total_price" => rand(100, 1000), "status" => "Pending", "order_date" => "2025-02-27"],
    ["id" => rand(1000, 9999), "customer_name" => "Mike Johnson", "total_price" => rand(75, 800), "status" => "Shipped", "order_date" => "2025-02-26"],
    ["id" => rand(1000, 9999), "customer_name" => "Sarah Lee", "total_price" => rand(40, 600), "status" => "Processing", "order_date" => "2025-02-25"],
    ["id" => rand(1000, 9999), "customer_name" => "Tom Hanks", "total_price" => rand(90, 700), "status" => "Completed", "order_date" => "2025-02-24"],
] : [];

// Fetch real data if connected
if ($pdo) {
    // Get total users
    $user_query = $pdo->prepare("SELECT COUNT(*) AS total_users FROM Users");
    $user_query->execute();
    $user_result = $user_query->fetch(PDO::FETCH_ASSOC);
    $total_users = $user_result['total_users'];

    // Get total orders
    $order_query = $pdo->prepare("SELECT COUNT(*) AS total_orders FROM Orders");
    $order_query->execute();
    $order_result = $order_query->fetch(PDO::FETCH_ASSOC);
    $total_orders = $order_result['total_orders'];

    // Get total products
    $product_query = $pdo->prepare("SELECT COUNT(*) AS total_products FROM Products");
    $product_query->execute();
    $product_result = $product_query->fetch(PDO::FETCH_ASSOC);
    $total_products = $product_result['total_products'];

    // Get total revenue
    $revenue_query = $pdo->prepare("SELECT SUM(total_price) AS total_revenue FROM Orders WHERE order_status = 'Completed'");
    $revenue_query->execute();
    $revenue_result = $revenue_query->fetch(PDO::FETCH_ASSOC);
    $total_revenue = $revenue_result['total_revenue'] ?? 0;

    // Fetch recent orders with user details
    $recent_orders_query = $pdo->prepare("SELECT o.order_id, u.first_name, u.last_name, o.total_price, o.order_status, o.created_at 
                                          FROM Orders o 
                                          JOIN Users u ON o.user_id = u.user_id 
                                          ORDER BY o.created_at DESC LIMIT 5");
    $recent_orders_query->execute();
    $recent_orders = $recent_orders_query->fetchAll(PDO::FETCH_ASSOC);
}
?>

<div class="dashboard">
    <h2>Admin Dashboard</h2>

    <div class="stats">
        <div class="stat-box">
            <h3>Total Users</h3>
            <p><?php echo $total_users; ?></p>
        </div>
        <div class="stat-box">
            <h3>Total Orders</h3>
            <p><?php echo $total_orders; ?></p>
        </div>
        <div class="stat-box">
            <h3>Total Products</h3>
            <p><?php echo $total_products; ?></p>
        </div>
        <div class="stat-box">
            <h3>Total Revenue</h3>
            <p>$<?php echo number_format($total_revenue, 2); ?></p>
        </div>
    </div>

    <h3>Recent Orders</h3>
    <?php if ($use_dummy_data) { ?>
        <p style="color: red;">Database not connected. Showing sample data.</p>
    <?php } ?>
    <table>
        <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Total Price</th>
            <th>Status</th>
            <th>Date</th>
        </tr>
        <?php if (empty($recent_orders)) { ?>
            <tr>
                <td colspan="5">No recent orders available</td>
            </tr>
        <?php } else { ?>
            <?php foreach ($recent_orders as $order) { ?>
                <tr>
                    <td>#<?php echo $order['order_id']; ?></td>
                    <td><?php echo $order['first_name'] . ' ' . $order['last_name']; ?></td>
                    <td>$<?php echo number_format($order['total_price'], 2); ?></td>
                    <td><?php echo ucfirst($order['order_status']); ?></td>
                    <td><?php echo $order['created_at']; ?></td>
                </tr>
            <?php } ?>
        <?php } ?>
    </table>
</div>

<style>
    .dashboard {
        padding: 20px;
    }
    .stats {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }
    .stat-box {
        background: #f4f4f4;
        padding: 15px;
        border-radius: 8px;
        text-align: center;
        width: 150px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    table, th, td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: left;
    }
    th {
        background: #eee;
    }
</style>
