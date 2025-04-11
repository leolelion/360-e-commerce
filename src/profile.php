<?php
session_start();
require_once 'config.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';

// Handle profile picture upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_picture'])) {
    $file = $_FILES['profile_picture'];
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $max_size = 5 * 1024 * 1024; // 5MB

    if ($file['error'] === UPLOAD_ERR_OK) {
        if (!in_array($file['type'], $allowed_types)) {
            $_SESSION['error'] = 'Invalid file type. Please upload a JPEG, PNG, or GIF image.';
        } elseif ($file['size'] > $max_size) {
            $_SESSION['error'] = 'File is too large. Maximum size is 5MB.';
        } else {
            $upload_dir = '../uploads/profile_pictures/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $new_filename = 'profile_' . $user_id . '_' . time() . '.' . $file_extension;
            $upload_path = $upload_dir . $new_filename;

            if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                $relative_path = 'uploads/profile_pictures/' . $new_filename;
                
                try {
                    $stmt = $pdo->prepare("UPDATE Users SET profile_picture = :profile_picture WHERE user_id = :user_id");
                    $stmt->execute([
                        ':profile_picture' => $relative_path,
                        ':user_id' => $user_id
                    ]);
                    $_SESSION['success'] = 'Profile picture updated successfully!';
                } catch (PDOException $e) {
                    $_SESSION['error'] = 'Error updating profile picture: ' . $e->getMessage();
                }
            } else {
                $_SESSION['error'] = 'Error uploading file.';
            }
        }
        header("Location: profile.php");
        exit();
    }
}

try {
    $stmt = $pdo->prepare("SELECT first_name, email, profile_picture FROM Users WHERE user_id = :user_id");
    $stmt->execute([':user_id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

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
    <title>Profile - Shop.co</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/profile.css">
</head>
<body>
<?php include 'header.php'; ?>

<main>
    <h1>Your Account</h1>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="message success">
            <?= htmlspecialchars($_SESSION['success']) ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="message error">
            <?= htmlspecialchars($_SESSION['error']) ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="profile-container">
        <div class="profile-sidebar">
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="file" id="profile-picture-upload" name="profile_picture" accept="image/*">
                <div class="profile-picture-container" onclick="document.getElementById('profile-picture-upload').click()">
                    <img src="<?= htmlspecialchars($user['profile_picture']) ?>" 
                         alt="Profile Picture" 
                         class="profile-picture"
                         onerror="this.src='../assets/images/default-profile.png'">
                    <div class="profile-picture-overlay">
                        <span>Click to change<br>profile picture</span>
                    </div>
                </div>
            </form>
        </div>

        <div class="account-info">
            <form action="update_profile.php" method="POST">
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" 
                           value="<?= htmlspecialchars($user['first_name']); ?>" required>
                    <button type="submit" name="change_first_name">Update First Name</button>
                </div>
            </form>

            <form action="update_profile.php" method="POST">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" 
                           value="<?= htmlspecialchars($user['email']); ?>" required>
                    <button type="submit" name="change_email">Update Email</button>
                </div>
            </form>

            <form action="update_profile.php" method="POST">
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" id="password" name="password" required>
                    <button type="submit" name="change_password">Change Password</button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>

<script>
document.getElementById('profile-picture-upload').addEventListener('change', function() {
    if (this.files && this.files[0]) {
        this.form.submit();
    }
});
</script>
</body>
</html>
