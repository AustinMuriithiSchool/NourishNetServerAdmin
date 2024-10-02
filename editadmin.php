<?php
session_start();
include 'db_connect.php';

// Redirect to index.html if user is not logged in or not an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: index.html");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    if (empty($username) || empty($email)) {
        die("Please fill in all fields.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    $query = "UPDATE users SET username=?, email=?";
    $params = array($username, $email);
    if ($password) {
        $query .= ", password=?";
        $params[] = $password;
    }
    $query .= " WHERE user_id=?";

    $params[] = $user_id;

    $stmt = $conn->prepare($query);
    $stmt->bind_param(str_repeat("s", count($params)), ...$params);

    if ($stmt->execute()) {
        header("Location: admin.php");  // Redirect to admin dashboard after update
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch admin user details (optional: you can fetch username and email if needed for display)
$stmt = $conn->prepare("SELECT username, email FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../styling/editadmin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>

    <!-- Navigation Bar -->
    <div class="taskbar">
        <div class="logo">NourishNet</div>
        <div class="navbar">
            <a href="admin.php"><i class="fas fa-home"></i>home</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i>logout</a>
        </div>
    </div>

    <!-- Edit Profile Form -->
    <div class="container">
        <div class="edit-box">
            <h2>Edit Profile</h2>
            <form method="post" action="editadmin.php">
                <div class="textbox">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>">
                </div>
                <div class="textbox">
                    <label for="email">Email:</label>
                    <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                </div>
                <div class="textbox">
                    <label for="password">New Password (leave blank to keep current):</label>
                    <input type="password" id="password" name="password">
                </div>
                <input type="submit" class="btn" value="Update">
            </form>
        </div>
    </div>

    <!-- Footer Section -->
    <footer class="footer">
        <p>&copy; 2024 Nutritionist. All rights reserved.</p>
    </footer>
</body>

</html>
