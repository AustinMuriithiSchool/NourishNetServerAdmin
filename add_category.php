<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
    <link rel="stylesheet" href="../styling/add_category.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <header class="header">
        <a href="#" class="logo">Nutritionist</a>
        <nav class="navbar">
            <a href="nutrition.php"><i class="fas fa-home"></i>home</a>
            <a href="upload.php"><i class="fas fa-cloud-upload-alt"></i>upload</a>
        </nav>
    </header>

    <section class="add-category">
        <h2 class="heading">Add New Category</h2>
        <form action="add_category.php" method="post" class="category-form">
            <div class="input-group">
                <label for="category_name">Category Name:</label>
                <input type="text" id="category_name" name="category_name" required>
            </div>
            <button type="submit" class="btn">Add Category</button>
        </form>


        <?php
        session_start();

        // Redirect to login if user is not authenticated
        if (!isset($_SESSION['username'])) {
            header("Location: index.html");
            exit();
        }
        // Include database connection
        include ('db_connect.php');

        // Check if the form was submitted using POST method
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Retrieve form data
            $category_name = $_POST['category_name'];

            // Insert into categories table
            $stmt = $conn->prepare("INSERT INTO categories (category_name) VALUES (?)");
            $stmt->bind_param("s", $category_name);
            $stmt->execute();

            // Close statement and database connection
            $stmt->close();
            $conn->close();

            // Redirect back to add_category.php (or any other page)
            header('Location: add_category.php');
            exit; // Ensure no further output is sent after redirection
        }
        ?>
    </section>
    <!-- Footer Section -->
    <footer class="footer">
        <p>&copy; 2024 NourishNet. All rights reserved.</p>
    </footer>
</body>

</html>