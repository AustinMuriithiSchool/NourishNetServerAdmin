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
    $stmt = $conn->prepare("SELECT user_id, password, user_type FROM users WHERE username = ?");
    if (!$stmt) {
        die("Preparation failed: " . $conn->error);
    }
    $stmt->bind_param("s", $username);

    // Execute the statement
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $hashed_password, $user_type);

    // Check if user exists and verify password
    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            // Set session variables
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['user_type'] = $user_type;

            // Redirect based on user type
            if ($user_type == 'admin') {
                header("Location: admin.php");
                exit();
            } else if ($user_type == 'nutritionist') {
                header("Location:nutrition.php ");
                exit();
            } else {
                header("Location: user.php");
                exit();
            }

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
    <link rel="stylesheet" href="../styling/login.css"> <!-- Link to your external CSS file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="taskbar">
        <div class="taskbar-left">
            <h1>NourishNet</h1>
        </div>
        <nav class="navbar">
            <a href="index.html"><i class="fas fa-home"></i>Home</a>
        </nav>
        <div class="taskbar-right">
        </div>
    </div>
    <div class="content">
        <div class="container">
            <div class="login-box">
                <h2>Login</h2>
                <form method="post" action="login.php">
                    <div class="textbox">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username">
                    </div>
                    <div class="textbox">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password">
                    </div>
                    <input type="submit" class="btn" value="Login">
                </form>
                <div class="register-link">
                    <a href="register.php">New user? Please sign up here!</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>