<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Check for empty fields
    if (empty($username) || empty($password)) {
        die("Please fill in all fields.");
    }

    // Prepare and bind
    $stmt = $conn->prepare("SELECT user_id, password FROM users WHERE username = ?");
    if (!$stmt) {
        die("Preparation failed: " . $conn->error);
    }
    $stmt->bind_param("s", $username);

    // Execute the statement
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $hashed_password);

    // Check if user exists and verify password
    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            // Set session variable
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;

            // Redirect to user dashboard or home page
            header("Location: user_dashboard.php");
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with that username.";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <h2>Login</h2>
    <form method="post" action="login.php">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Login">
    </form>
    <div>
        <a href="register.php">New user? Please sign up here!</a>
    </div>
</body>

</html>
