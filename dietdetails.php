<?php
session_start();

// Redirect to login if user is not authenticated
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

// Check user type to determine dashboard link
$user_type = $_SESSION['user_type']; // Assuming 'user_type' is set in your session upon login

// Define default dashboard URL
$home_url = 'diet.php'; // Default to diet.php for all users unless overridden

// Redirect to user.php if user type is 'user'
if ($user_type === 'user') {
    $home_url = 'user.php';
}

// Include database connection
include('db_connect.php');

// Initialize variables to store food details
$food_name = $description = $calories = $protein = $fat = $carbohydrates = $recipe = $categories = $image_url = '';

// Retrieve food_id from URL parameter
if (isset($_GET['food_id'])) {
    $food_id = $_GET['food_id'];

    // Query to fetch detailed information about the selected food item, including categories
    $sql = "SELECT f.food_id, f.food_name, f.description, f.image_url, nv.calories, nv.protein, nv.fat, nv.carbohydrates, f.recipe, GROUP_CONCAT(c.category_name SEPARATOR ', ') AS categories
            FROM foods f
            INNER JOIN nutritional_values nv ON f.food_id = nv.food_id
            INNER JOIN food_categories fc ON f.food_id = fc.food_id
            INNER JOIN categories c ON fc.category_id = c.category_id
            WHERE f.food_id = $food_id
            GROUP BY f.food_id"; // Adjust query as per your schema

    $result = $conn->query($sql);

    // Check if there are results
    if ($result->num_rows > 0) {
        // Fetch and store food details
        $row = $result->fetch_assoc();
        $food_name = $row["food_name"];
        $description = $row["description"];
        $calories = $row["calories"];
        $protein = $row["protein"];
        $fat = $row["fat"];
        $carbohydrates = $row["carbohydrates"];
        $recipe = $row["recipe"];
        $categories = $row["categories"];
        $image_url = $row["image_url"];
    } else {
        $error_message = "Food not found.";
    }
} else {
    $error_message = "Invalid food selection.";
}

// Close database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Details</title>
    <link rel="stylesheet" href="../styling/dietdetails.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <header class="header">
        <a href="#" class="logo">NourishNet</a>
        <nav class="navbar">
            <a href="<?php echo $home_url; ?>"><i class="fas fa-folder-open"></i> Back to diet List</a>
            <!-- Link to view uploaded foods only for nutritionist -->
            <?php if ($user_type == 'nutritionist'): ?>
                <a href="upload.php"><i class="fas fa-cloud-upload-alt"></i> upload</a>
                <a href="consultation.php"><i class="fas fa-comments"></i> chat</a>
            <?php endif; ?>
        </nav>
    </header>

    <section class="food-details">
        <h2 class="heading">Food Details</h2>
        <?php if (isset($error_message)): ?>
            <p><?php echo $error_message; ?></p>
        <?php else: ?>
            <div class="details">
                <h3><?php echo $food_name; ?></h3>
                <p><strong>Description:</strong> <?php echo $description; ?></p>
                <p><strong>Calories:</strong> <?php echo $calories; ?></p>
                <p><strong>Protein:</strong> <?php echo $protein; ?>g</p>
                <p><strong>Fat:</strong> <?php echo $fat; ?>g</p>
                <p><strong>Carbohydrates:</strong> <?php echo $carbohydrates; ?>g</p>
                <p><strong>Recipe:</strong></p>
                <ol>
                    <?php
                    // Explode recipe into array based on new lines
                    $recipe_steps = explode("\n", $recipe);

                    // Output each step as ordered list item without double numbering
                    foreach ($recipe_steps as $step) {
                        // Remove any existing numbering from the step
                        $step = preg_replace('/^\d+\.\s*/', '', $step);
                        echo "<li>$step</li>";
                    }
                    ?>
                </ol>
                <p><strong>Categories:</strong> <?php echo $categories; ?></p>
                <img src="<?php echo $image_url; ?>" alt="<?php echo $food_name; ?>" style="max-width: 400px;">
            </div>
        <?php endif; ?>
    </section>

    <!-- Footer Section -->
    <footer class="footer">
        <p>&copy; 2024 Nutritionist. All rights reserved.</p>
    </footer>
</body>

</html>
