<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("SELECT profile_picture, email, first_name, last_name, role, created_at FROM Users WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        session_destroy();
        header("Location: login.php");
        exit();
    }

    if (empty($user['profile_picture'])) {
        $user['profile_picture'] = '../assets/images/default-profile.jpg';
    } else {
        $user['profile_picture'] = '../' . $user['profile_picture'];
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
    <title>User Profile - ShopCo</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/profile_info.css">
    <style>
        .profile-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .profile-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .profile-picture {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto;
            display: block;
            border: 3px solid #4CAF50;
        }

        .profile-info {
            margin-top: 20px;
        }

        .info-item {
            margin-bottom: 15px;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 4px;
        }

        .info-label {
            font-weight: bold;
            color: #333;
            display: block;
            margin-bottom: 5px;
        }

        .info-value {
            color: #666;
        }

        .profile-actions {
            margin-top: 20px;
            text-align: center;
        }

        .profile-actions a {
            display: inline-block;
            padding: 10px 20px;
            margin: 0 10px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .profile-actions a:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

<main>
    <div class="profile-container">
        <div class="profile-header">
            <img src="<?= htmlspecialchars($user['profile_picture']) ?>" 
                 alt="Profile Picture" 
                 class="profile-picture"
                 onerror="this.src='../assets/images/default-profile.png'">
            <h2><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></h2>
        </div>

        <div class="profile-info">
            <div class="info-item">
                <span class="info-label">Email:</span>
                <span class="info-value"><?= htmlspecialchars($user['email']) ?></span>
            </div>

            <div class="info-item">
                <span class="info-label">Role:</span>
                <span class="info-value"><?= htmlspecialchars(ucfirst($user['role'])) ?></span>
            </div>

            <div class="info-item">
                <span class="info-label">Member Since:</span>
                <span class="info-value"><?= date("F j, Y", strtotime($user['created_at'])) ?></span>
            </div>
        </div>

        <div class="profile-actions">
            <a href="profile.php">Edit Profile</a>
            <a href="index.php">Return to Home</a>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>
</body>
</html>
