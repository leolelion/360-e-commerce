<?php
session_start();
require_once 'config.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    
    $stmt = $pdo->prepare("SELECT profile_picture, email, first_name, role, created_at FROM Users WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // if user not found, force logout, included it twice cause its so nice
    if (!$user) {
        session_destroy();
        header("Location: login.php");
        exit();
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/profile_info.css">
</head>
<body>
<?php include 'header.php'; ?>
<main>
<div class="profile-container">
    <h2>User Profile</h2>
    <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture">
    <p class="info"><strong>Name:</strong> <?php echo htmlspecialchars($user['first_name']); ?></p>
    <p class="info"><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
    <p class="info"><strong>Role:</strong> <?php echo htmlspecialchars($user['role']); ?></p>
    <p class="info"><strong>Joined On:</strong> <?php echo date("F j, Y", strtotime($user['created_at'])); ?></p>
    <a href="index.php">Home</a> <br>
    <a href="logout.php">Logout</a> <br>
    <a href="profile.php">Edit Profile</a> <br>
</div>
</main>
<?php include 'footer.php'; ?>
</body>
</html>
