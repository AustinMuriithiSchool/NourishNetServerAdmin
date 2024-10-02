<?php
// Include your database configuration file here
include 'db_connect.php';

// Initialize variables to store user input
$username = $password = $email = $user_type = '';
$username_err = $password_err = $email_err = $user_type_err = '';

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        $username = htmlspecialchars(trim($_POST['username']));
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } else {
        $password = htmlspecialchars(trim($_POST['password']));
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email.";
    } else {
        $email = htmlspecialchars(trim($_POST['email']));
        // Check if email format is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email_err = "Invalid email format.";
        }
    }

    // Validate user type
    if (empty(trim($_POST["user_type"]))) {
        $user_type_err = "Please select a user type.";
    } else {
        $user_type = htmlspecialchars(trim($_POST['user_type']));
    }

    // Check input errors before inserting into database
    if (empty($username_err) && empty($password_err) && empty($email_err) && empty($user_type_err)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and bind the insertion query
        $stmt = $conn->prepare("INSERT INTO users (username, password, email, user_type) VALUES (?, ?, ?, ?)");
        if (!$stmt) {
            die("Preparation failed: " . $conn->error);
        }
        $stmt->bind_param("ssss", $username, $hashed_password, $email, $user_type);

        // Execute the statement
        if ($stmt->execute()) {
            // Registration successful, redirect to appropriate dashboard
            session_start();
            $_SESSION['user_id'] = $stmt->insert_id;
            $_SESSION['username'] = $username;
            $_SESSION['user_type'] = $user_type;

            // Redirect to login page
            header("Location: login.php");
            exit();



        } else {
            echo "Error: " . $stmt->error;
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="../styling/register.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>

<body>
    <div class="taskbar">
        <div class="taskbar-left">
            <h1>NourishNet</h1>
        </div>
        <nav class="navbar">
            <a href="login.php"><i class="fas fa-sign-in-alt"></i>login</a>
        </nav>
        <div class="taskbar-right">
            <!-- You can add links or other content here -->
        </div>
    </div>

    <div class="content">
        <div class="register-container">
            <h2>Register</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="textbox">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?php echo $username; ?>">
                    <span class="error"><?php echo $username_err; ?></span>
                </div>
                <div class="textbox">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" value="<?php echo $password; ?>">
                    <span class="error"><?php echo $password_err; ?></span>
                </div>
                <div class="textbox">
                    <label for="email">Email:</label>
                    <input type="text" id="email" name="email" value="<?php echo $email; ?>">
                    <span class="error"><?php echo $email_err; ?></span>
                </div>
                <div class="textbox">
                    <label for="user_type">User Type:</label>
                    <select id="user_type" name="user_type">
                        <option value="user" <?php if ($user_type == 'user')
                            echo 'selected'; ?>>User</option>
                        <option value="admin" <?php if ($user_type == 'admin')
                            echo 'selected'; ?>>Admin</option>
                        <option value="nutritionist" <?php if ($user_type == 'nutritionist')
                            echo 'selected'; ?>>
                            Nutritionist</option>
                    </select>
                    <span class="error"><?php echo $user_type_err; ?></span>
                </div>
                <input type="submit" class="btn" value="Register">
            </form>
        </div>
    </div>
</body>

</html>