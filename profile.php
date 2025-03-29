<?php
session_start();
require_once 'config.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT first_name, email FROM Users WHERE user_id = :user_id");
$stmt->execute([':user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Shop.co</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/profile.css">
    <script defer src="script.js"></script>
</head>
<body>
<?php include 'header.php'; ?>

<main>
    <h1>Your Account</h1>

    <?php
    if (isset($_SESSION['success'])) {
        echo "<p class='success'>" . $_SESSION['success'] . "</p>";
        unset($_SESSION['success']);
    }
    if (isset($_SESSION['error'])) {
        echo "<p class='error'>" . $_SESSION['error'] . "</p>";
        unset($_SESSION['error']);
    }
    ?>

    <section class="account-info">
        <form action="update_profile.php" method="POST">
            <div>
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($user['first_name']); ?>" required>
                <button type="submit" name="change_first_name">Update First Name</button>
            </div>
        </form>

        <form action="update_profile.php" method="POST">
            <div>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>
                <button type="submit" name="change_email">Update Email</button>
            </div>
        </form>

        <form action="update_profile.php" method="POST">
            <div>
                <label for="password">New Password</label>
                <input type="password" id="password" name="password" required>
                <button type="submit" name="change_password">Change Password</button>
            </div>
        </form>
    </section>
</main>

<?php include 'footer.php'; ?>
</body>
</html>
