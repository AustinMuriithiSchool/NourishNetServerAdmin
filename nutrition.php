<?php
session_start();

// Redirect to login if user is not authenticated
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

if (!isset($_SESSION['username'])) {
    header("Location: index.html"); // Redirect to login page if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nutritionist Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../styling/nutrition.css">
</head>
<body>
    <!-- Header Section -->
    <header class="header">
        <a href="#" class="logo">NourishNet</a>
        <nav class="navbar">
            <a href="#profile update"><i class="fas fa-user-edit"></i>edit profile</a>
            <a href="#upload-diet"><i class="fas fa-cloud-upload-alt"></i>upload</a>
            <a href="#consultation"><i class="fas fa-comments"></i>chat</a>
            <a href="#view-uploads"><i class="fas fa-folder-open"></i>view</a>
            <a href="#add-category"><i class="fas fa-list"></i>category</a>
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
                <h3>Nutritionist Dashboard</h3>
                <p>Use the links below to manage diet plans and consultations for your clients.</p>
            </div>
        </div>
    </section>

    <!-- Cards Section -->
    <section class="services">
        <div class="box-container">
        <div class="box" id="profile update">
                <div class="icon">
                    <img src="images/edit-profile.png" alt="profile Icon">
                </div>
                <div class="content">
                    <h3> update profile</h3>
                    <p>Update your profile here.</p>
                    <a href="editnutritionist.php" class="btn">edit profile</a>
                </div>
            </div>

            <div class="box" id="upload-diet">
                <div class="icon">
                    <img src="images/upload-icon.png" alt="Diet Icon">
                </div>
                <div class="content">
                    <h3>Upload Diet Plan</h3>
                    <p>Upload a new diet plan for your clients, tailored to their specific health conditions.</p>
                    <a href="upload.php" class="btn">Go to Upload</a>
                </div>
            </div>

            <div class="box" id="consultation">
                <div class="icon">
                    <img src="images/consultation-icon.png" alt="Consultation Icon">
                </div>
                <div class="content">
                    <h3> Consultation</h3>
                    <p>Consult and advice your clients .</p>
                    <a href="consultation.php" class="btn">go to consultations</a>
                </div>
            </div>
            
            <div class="box" id="view-uploads">
                <div class="icon">
                    <img src="images/diet-icon.png" alt="View Icon">
                </div>
                <div class="content">
                    <h3>View diets</h3>
                    <p>View uploaded diet descriptions.</p>
                    <a href="../diet.php" class="btn">Go to View</a>
                </div>
            </div>

            <div class="box" id="add-category">
                <div class="icon">
                    <img src="images/category-icon.png" alt="Category Icon">
                </div>
                <div class="content">
                    <h3>Add Category</h3>
                    <p>Add disease category.</p>
                    <a href="add_category.php" class="btn">Go to Add Category</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer Section -->
    <footer class="footer">
        <p>&copy; 2024 NourishNet. All rights reserved.</p>
    </footer>

    <!-- JavaScript for Hiding Welcome Message -->
    <script>
        // Function to hide the welcome message after 5 seconds
        function hideWelcomeMessage() {
            const welcomeMessage = document.getElementById('welcome-message');
            welcomeMessage.style.display = 'none';
        }

        // Call the function to hide the message after page load
        window.onload = function() {
            setTimeout(hideWelcomeMessage, 5000); // Adjust time duration as needed (5000 milliseconds = 5 seconds)
        };
    </script>
</body>
</html>
