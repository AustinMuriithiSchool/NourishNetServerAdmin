<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>

    <h2>Register</h2>

    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    include 'db_connect.php';  // Ensure this file connects to your database correctly
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the submitted username and password
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Simple validation
        if (empty($username) || empty($password)) {
            echo "Please fill in both fields.";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Insert user data into the database
            $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $username, $hashed_password);

            if ($stmt->execute()) {
                echo "Registration successful! <br>";
                echo "Username: " . htmlspecialchars($username) . "<br>";
            } else {
                echo "Error: Could not register user.";
            }

            $stmt->close();
            $conn->close();
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
