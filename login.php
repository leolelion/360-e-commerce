<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
<?php include 'header.php'; ?>

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
<script src="js/scripts.js"></script>
</body>
</html>
