<?php
session_start();

// Redirect to login if user is not authenticated
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}

// Ensure user is a nutritionist (user_type = 'nutritionist')
if ($_SESSION['user_type'] !== 'nutritionist') {
    header("Location: index.html");
    exit();
}

// Include database connection
include('db_connect.php');

// Retrieve user_id from session
$user_id = $_SESSION['user_id'];

// Check if the form was submitted using POST method
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $food_name = $_POST['food_name'];
    $description = $_POST['description'];
    $recipe = $_POST['recipe'];
    $categories = $_POST['categories']; // Array of category IDs
    $calories = $_POST['calories'];
    $protein = $_POST['protein'];
    $fat = $_POST['fat'];
    $carbohydrates = $_POST['carbohydrates'];
    $vitamins = $_POST['vitamins'];
    $minerals = $_POST['minerals'];

    // Handle image upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);

    // Check if file was successfully uploaded
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // File uploaded successfully, proceed with database operations

        // Insert into foods table
        $stmt = $conn->prepare("INSERT INTO foods (food_name, description, image_url, recipe, user_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $food_name, $description, $target_file, $recipe, $user_id);
        $stmt->execute();
        $food_id = $stmt->insert_id;

        // Insert into nutritional_values table
        $stmt = $conn->prepare("INSERT INTO nutritional_values (food_id, calories, protein, fat, carbohydrates, vitamins, minerals) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iddddss", $food_id, $calories, $protein, $fat, $carbohydrates, $vitamins, $minerals);
        $stmt->execute();

        // Insert into food_categories table
        foreach ($categories as $category_id) {
            $stmt = $conn->prepare("INSERT INTO food_categories (food_id, category_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $food_id, $category_id);
            $stmt->execute();
        }

        // Close statement
        $stmt->close();

        // Close database connection
        $conn->close();

        // Redirect back to upload.php (or any other page)
        header('Location: upload.php');
        exit; // Ensure no further output is sent after redirection
    } else {
        // Error uploading file
        echo "Error uploading file.";
    }
    exit();
}

// Fetch categories from the database
$sql = "SELECT category_id, category_name FROM categories";
$result = $conn->query($sql);

$categories = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
} else {
    $categories = ['message' => 'No categories found'];
}

// Close database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Food</title>
    <link rel="stylesheet" href="../styling/upload.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .input-group {
            margin-bottom: 1rem;
        }
        .input-group label {
            display: block;
            margin-bottom: 0.5rem;
        }
        .input-group input, .input-group textarea, .input-group select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 0.25rem;
        }
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 0.25rem;
            cursor: pointer;
            text-align: center;
        }
        .btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <header class="header">
        <a href="#" class="logo">NourishNet</a>
        <nav class="navbar">
            <a href="nutrition.php"><i class="fas fa-home"></i>home</a>
            <a href="consultation.php"><i class="fas fa-comments"></i>chat</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i>logout</a>
        </nav>
    </header>

    <section class="upload">
        <h2 class="heading">Upload Food</h2>
        <form action="upload.php" method="post" enctype="multipart/form-data" class="upload-form">
            <div class="input-group">
                <label for="food_name">Food Name:</label>
                <input type="text" id="food_name" name="food_name" required>
            </div>
            <div class="input-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea>
            </div>
            <div class="input-group">
                <label for="image">Image:</label>
                <input type="file" id="image" name="image" accept="image/*" required>
            </div>
            <div class="input-group">
                <label for="recipe">Recipe:</label>
                <textarea id="recipe" name="recipe" required></textarea>
                <button type="button" onclick="formatRecipe()" class="btn">Format Recipe</button>
            </div>
            <div class="input-group">
                <label for="categories">Categories:</label>
                <select id="categories" name="categories[]" multiple required>
                    <?php
                    foreach ($categories as $category) {
                        echo '<option value="' . $category['category_id'] . '">' . $category['category_name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="input-group">
                <label for="calories">Calories:</label>
                <input type="number" id="calories" name="calories" step="0.01" required>
            </div>
            <div class="input-group">
                <label for="protein">Protein (g):</label>
                <input type="number" id="protein" name="protein" step="0.01" required>
            </div>
            <div class="input-group">
                <label for="fat">Fat (g):</label>
                <input type="number" id="fat" name="fat" step="0.01" required>
            </div>
            <div class="input-group">
                <label for="carbohydrates">Carbohydrates (g):</label>
                <input type="number" id="carbohydrates" name="carbohydrates" step="0.01" required>
            </div>
            <div class="input-group">
                <label for="vitamins">Vitamins:</label>
                <input type="text" id="vitamins" name="vitamins">
            </div>
            <div class="input-group">
                <label for="minerals">Minerals:</label>
                <input type="text" id="minerals" name="minerals">
            </div>
            <button type="submit" class="btn">Upload Food</button>
        </form>
    </section>

    <!-- Footer Section -->
    <footer class="footer">
        <p>&copy; 2024 Nutritionist. All rights reserved.</p>
    </footer>

    <script>
        function formatRecipe() {
            const recipeTextarea = document.getElementById('recipe');
            const lines = recipeTextarea.value.split('\n');
            const formattedLines = lines.map((line, index) => `${index + 1}. ${line.trim()}`).join('\n');
            recipeTextarea.value = formattedLines;
        }
    </script>
</body>
</html>
