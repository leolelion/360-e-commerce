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

    <main>
        <div class="registration-form">
            <?php
            if (isset($_GET['error'])) {
                if ($_GET['error'] == "invalid_credentials") {
                    echo "<p id='error-message'>Invalid email or password. Please try again.</p>";
                } elseif ($_GET['error'] == "db_error") {
                    echo "<p id='error-message'>Database error. Please try again later.</p>";
                }
            }
            ?>

            <form action="loginform.php" method="POST">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Login</button>
            </form>
        </div>
    </main>

    <script src="../assets/jsscripts.js"></script>
</body>
</html>