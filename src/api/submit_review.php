<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once '../config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Please log in to submit a review']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$required_fields = ['product_id', 'rating', 'comment'];
foreach ($required_fields as $field) {
    if (!isset($_POST[$field]) || empty($_POST[$field])) {
        http_response_code(400);
        echo json_encode(['error' => "Missing required field: $field"]);
        exit();
    }
}

$user_id = $_SESSION['user_id'];
$product_id = intval($_POST['product_id']);
$rating = intval($_POST['rating']);
$comment = trim($_POST['comment']);

if ($rating < 1 || $rating > 5) {
    http_response_code(400);
    echo json_encode(['error' => 'Rating must be between 1 and 5']);
    exit();
}

try {
    $stmt = $pdo->prepare("SELECT COUNT(*) as review_count FROM Reviews WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result['review_count'] >= 5) {
        http_response_code(400);
        echo json_encode(['error' => 'You have reached the maximum limit of 5 reviews for this product']);
        exit();
    }

    $stmt = $pdo->prepare("INSERT INTO Reviews (user_id, product_id, rating, comment) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $product_id, $rating, $comment]);
    
    $stmt = $pdo->prepare("
        SELECT r.review_id, r.rating, r.comment, r.created_at, 
               CONCAT(u.first_name, ' ', u.last_name) AS username 
        FROM Reviews r
        JOIN Users u ON r.user_id = u.user_id
        WHERE r.review_id = LAST_INSERT_ID()
    ");
    $stmt->execute();
    $review = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'review' => $review,
        'reviews_remaining' => 5 - ($result['review_count'] + 1)
    ]);
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'An error occurred while saving your review. Please try again.']);
} 