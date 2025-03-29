<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>
<?php include 'header.php'; ?>
<?php
if (isset($_GET['error'])) {
    if ($_GET['error'] == "invalid_credentials") {
        echo "<p style='color:red;'>Invalid email or password. Please try again.</p>";
    } elseif ($_GET['error'] == "db_error") {
        echo "<p style='color:red;'>Database error. Please try again later.</p>";
    }
}
?>


    <main>
        <form action="loginform.php" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <div id="error-message"></div>

            <button type="submit">Login</button>
        </form>

    </main>
<script src="../assets/jsscripts.js"></script>
</body>
</html>
