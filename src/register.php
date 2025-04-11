<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - ShopCo</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/register.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <main>
        <div class="registration-form">
            <?php
            if (isset($_GET['error'])) {
                if ($_GET['error'] == "email_exists") {
                    echo "<p id='error-message'>Email already exists. Please use a different email.</p>";
                } elseif ($_GET['error'] == "db_error") {
                    echo "<p id='error-message'>Database error. Please try again later.</p>";
                }
            }
            ?>

            <form action="registerform.php" method="POST">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" required>

                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>

                <label for="phone">Phone:</label>
                <input type="tel" id="phone" name="phone">

                <label for="address">Address:</label>
                <textarea id="address" name="address"></textarea>

                <button type="submit">Register</button>
            </form>
        </div>
    </main>

    <script src="../assets/jsscripts.js"></script>
</body>
</html>
