<?php
session_start();

// Redirect to login if user is not authenticated
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}
include 'db_connect.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: loginusers.php");
    exit();
}

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch users
$sql = "SELECT user_id, username, email, user_type FROM users";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Users</title>
    <link rel="stylesheet" href="../styling/adminviewusers.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <div class="taskbar">
        <div class="taskbar-left">
            <h1>NourishNet</h1>
        </div>
        <div class="taskbar-right">
            <a href="admin.php"><i class="fas fa-home"></i>Home</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i>logout</a>
        </div>
    </div>
    <div class="container">
        <h1>All Users</h1>
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>User Type</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $row["user_id"] . "</td>
                                <td>" . $row["username"] . "</td>
                                <td>" . $row["email"] . "</td>
                                <td>" . $row["user_type"] . "</td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No users found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

<footer class="footer">
    <p>&copy; 2024 NourishNet. All rights reserved.</p>
</footer>

</html>

<?php
$conn->close();
?>