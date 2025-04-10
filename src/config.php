<?php
// Set up base URL for use in HTML (for images, CSS, etc.)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$scriptName = $_SERVER['SCRIPT_NAME']; 
$baseDir = explode('/src', $scriptName)[0]; 
define('BASE_URL', "$protocol://$host$baseDir/");

// Set up base path for file includes (for PHP)
define('BASE_PATH', __DIR__ . '/');

// DB connection (PDO)
$host = '127.0.0.1';
$dbname = 'forests';
$user = 'forests';
$password = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("PDO connection failed: " . $e->getMessage());
}

// DB connection (MySQLi)
$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("MySQLi connection failed: " . $conn->connect_error);
}
?>