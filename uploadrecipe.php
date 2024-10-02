<?php
session_start();

// Redirect to login if user is not authenticated
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}
include 'db_connect.php';

$message = '';

if (!isset($_SESSION['user_id'])) {
    $message = "You must be logged in to upload a recipe.";
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $recipe = trim($_POST['recipe']);
    $description = trim($_POST['description']);
    $ingredients = trim($_POST['ingredients']);
    $instructions = trim($_POST['instructions']);
    $tag = trim($_POST['tag']);
    $nutrition_data = trim($_POST['nutrition']);
    $suitability = trim($_POST['suitability']);
    $image = '';

    // Server-side validation
    if (empty($recipe) || empty($description) || empty($ingredients) || empty($instructions) || empty($tag) || empty($nutrition_data) || empty($suitability)) {
        $message = "All fields are required.";
    } else {
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image = $target_file;
            } else {
                $message = "Sorry, there was an error uploading your file.";
            }
        }

        if (empty($message)) {
            try {
                $stmt = $conn->prepare("INSERT INTO recipe (user_id, recipe, recipe_description, recipe_ingredients, recipe_instructions, recipe_tag, recipe_image, recipe_nutrition, recipe_suitability) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("issssssss", $_SESSION['user_id'], $recipe, $description, $ingredients, $instructions, $tag, $image, $nutrition_data, $suitability);

                if ($stmt->execute()) {
                    $message = "New recipe uploaded successfully";
                } else {
                    $message = "Error: " . $conn->error;
                }
                $stmt->close();
            } catch (Exception $e) {
                $message = "Error: " . $e->getMessage();
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Upload Recipe</title>
    <link rel="stylesheet" href="../styling/uploadrecipe.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .error {
            color: red;
            font-size: 0.9em;
        }
    </style>
    <script>
        function validateForm() {
            let isValid = true;
            const requiredFields = ['recipe', 'description', 'ingredients', 'instructions', 'tag', 'nutrition', 'suitability'];
            requiredFields.forEach(field => {
                const fieldElement = document.forms["uploadForm"][field];
                const errorElement = document.getElementById(field + '-error');
                if (fieldElement.value.trim() === "") {
                    errorElement.textContent = "This field is required.";
                    isValid = false;
                } else {
                    errorElement.textContent = "";
                }
            });
            return isValid;
        }
    </script>
</head>

<body>
    <div class="taskbar">
        <div class="taskbar-left">
            <h1>NourishNet</h1>
        </div>
        <div class="taskbar-right">
            <button onclick="location.href='user.php'"><i class="fas fa-home"></i>home</button>
            <button onclick="location.href='logout.php'"><i class="fas fa-sign-out-alt"></i>logout</button>
        </div>
    </div>
    <div class="content">
        <h2>Upload Recipe</h2>
        <?php if (!empty($message)): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
        <form name="uploadForm" action="uploadrecipe.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
            <label for="recipe">Recipe Name:</label>
            <input type="text" id="recipe" name="recipe" required>
            <span class="error" id="recipe-error"></span><br>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>
            <span class="error" id="description-error"></span><br>

            <label for="ingredients">Ingredients:</label>
            <textarea id="ingredients" name="ingredients" required></textarea>
            <span class="error" id="ingredients-error"></span><br>

            <label for="instructions">Instructions:</label>
            <textarea id="instructions" name="instructions" required></textarea>
            <span class="error" id="instructions-error"></span><br>

            <label for="tag">Recipe Type:</label>
            <select name="tag" id="tag" required>
                <option value="">Select Type</option>
                <option value="Breakfast">Breakfast</option>
                <option value="Lunch">Lunch</option>
                <option value="Dinner">Dinner</option>
                <option value="Snack">Snack</option>
            </select>
            <span class="error" id="tag-error"></span><br>

            <label for="image">Recipe Image:</label>
            <input type="file" id="image" name="image"><br>

            <label for="nutrition">Nutrition Information:</label>
            <textarea id="nutrition" name="nutrition" required></textarea>
            <span class="error" id="nutrition-error"></span><br>

            <label for="suitability">Suitability:</label>
            <textarea id="suitability" name="suitability" required></textarea>
            <span class="error" id="suitability-error"></span><br>

            <input type="submit" value="Upload Recipe">
        </form>
    </div>
    <!-- Footer Section -->
    <footer class="footer">
        <p>&copy; 2024 Nutritionist. All rights reserved.</p>
    </footer>
</body>

</html>
