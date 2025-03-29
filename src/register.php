<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/register.css">


</head>

<body>

<main>
    <div class="registration-form">
        
        <form action="registerform.php" method="post" enctype="multipart/form-data">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" required>

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="profile_picture">Profile Picture:</label>
            <input type="file" id="profile_picture" name="profile_picture">
            
            <div id="error-message"></div>

            <div class="form-buttons">
                    <button type="submit" class="register-btn">Register</button>
                    <a href="index.php" class="home-btn">Back</a>
            </div>
        </form>
    </div>
</main>

</body>
</html>
