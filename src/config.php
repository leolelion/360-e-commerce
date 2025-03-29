<?php
$host = 'localhost';
$dbname = 'ShopCoDB';
$user = 'root';
$password = '';

try {
 
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
//bof connections
$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("MySQLi Connection failed: " . $conn->connect_error);
}
?>
