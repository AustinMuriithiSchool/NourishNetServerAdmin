<?php
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>

    <h2>Register</h2>

    <?php
    include 'db_connect.php';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the submitted username and password
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Simple validation
        if (empty($username) || empty($password)) {
            echo "Please fill in both fields.";
        } else {
            // In a real application, you would hash the password and save the user data to a database
            // Example: password_hash($password, PASSWORD_BCRYPT);
            // For now, we'll just display the submitted information
            echo "Registration successful! <br>";
            echo "Username: " . htmlspecialchars($username) . "<br>";
            echo "Password: " . htmlspecialchars($password) . "<br>";
        }
    }
    ?>

    <form action="" method="post">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Register">
    </form>

</body>
</html>