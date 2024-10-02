<?php
session_start();

// Redirect to login page if user is not authenticated
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

// Assign username from session to $username variable
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../styling/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <!-- Header Section -->
    <header class="taskbar">
        <a href="#" class="logo">NourishNet</a>
        <nav class="navbar">
            <a href="#edit-admin"><i class="fas fa-user-edit"></i>editprofile</a>
            <a href="#view-users"><i class="fas fa-users"></i>users</a>
            <a href="#view-recipes"><i class="fas fa-utensils"></i>recipes</a>
            <a href="#view-diets"><i class="fas fa-apple-alt"></i>diets</a>
            <a href="#check-analytics"><i class="fas fa-chart-line"></i>analytics</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i>logout</a>
        </nav>
        <div id="menu" class="fas fa-bars"></div>
    </header>

    <!-- Home Section -->
    <section class="home">
        <div class="content">
        </div>
    </section>

    <!-- Main Content Section -->
    <section class="about">
        <div class="row">
            <div class="content">
                <h3>Admin Dashboard</h3>
                <p>Use the links below to view users,recipes,diets and check analytics.</p>
            </div>
        </div>
    </section>

    <!-- Cards Section -->
    <section class="services">
        <div class="box-container">
            <div class="box" id="edit-admin">
                <div class="icon">
                    <img src="images/edit-profile.png" alt="Edit Profile Icon">
                </div>
                <div class="content">
                    <h3>Edit Profile</h3>
                    <p>Edit your admin profile details.</p>
                    <a href="editadmin.php?username=<?php echo htmlspecialchars($username); ?>" class="btn">Edit Profile</a>
                </div>
            </div>

            <div class="box" id="view-users">
                <div class="icon">
                    <img src="images/users.png" alt="View Users Icon">
                </div>
                <div class="content">
                    <h3>View Users</h3>
                    <p>Manage and view details of all users.</p>
                    <a href="adminviewusers.php" class="btn">View Users</a>
                </div>
            </div>

            <div class="box" id="view-recipes">
                <div class="icon">
                    <img src="images/recipe-icon.png" alt="View Recipes Icon">
                </div>
                <div class="content">
                    <h3>View Recipes</h3>
                    <p>View and manage all uploaded recipes.</p>
                    <a href="recipes.php" class="btn">View Recipes</a>
                </div>
            </div>

            <div class="box" id="view-diets">
                <div class="icon">
                    <img src="images/diet-icon.png" alt="View Diets Icon">
                </div>
                <div class="content">
                    <h3>View Diets</h3>
                    <p>View and manage all uploaded diet plans.</p>
                    <a href="diet.php" class="btn">View Diets</a>
                </div>
            </div>

            <div class="box" id="check-analytics">
                <div class="icon">
                    <img src="images/analytics.png" alt="Analytics Icon">
                </div>
                <div class="content">
                    <h3>Check Analytics</h3>
                    <p>Check website and user analytics.</p>
                    <a href="analytics.php" class="btn">Check Analytics</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer Section -->
    <footer class="footer">
        <p>&copy; 2024 NourishNet. All rights reserved.</p>
    </footer>
</body>

</html>
