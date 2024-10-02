<?php
session_start();

// Redirect to login if user is not authenticated
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}
include 'db_connect.php';

// Establish connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in and retrieve user type
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $sql_user = "SELECT user_type FROM users WHERE user_id = $user_id";
    $result_user = $conn->query($sql_user);

    if ($result_user->num_rows > 0) {
        $row_user = $result_user->fetch_assoc();
        $user_type = $row_user['user_type'];
    } else {
        // Handle error: user not found
        echo "User not found";
        exit;
    }
} else {
    // Redirect to login if user is not logged in
    header("Location: login.php");
    exit;
}

// Check if recipe ID is provided
if (isset($_GET['recipe_id']) && !empty($_GET['recipe_id'])) {
    $recipe_id = $_GET['recipe_id'];

    // Retrieve recipe details from the database
    $sql = "SELECT * FROM recipe WHERE recipe_id = $recipe_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $recipe_name = $row["recipe"];
        $description = $row["recipe_description"];
        $ingredients = explode("\n", $row["recipe_ingredients"]); // Split ingredients by newline
        $instructions = explode("\n", $row["recipe_instructions"]); // Split instructions by newline
        $tag = $row["recipe_tag"];
        $image = $row["recipe_image"];
        $recipe_nutrition = $row["recipe_nutrition"]; // Assuming 'recipe_nutrition' is a column in your recipe table
        $recipe_suitability = explode("\n", $row["recipe_suitability"]);
    } else {
        echo "Recipe not found";
        exit;
    }

    // Fetch all ratings and comments for the recipe
    $sql_ratings = "SELECT r.rating, r.rating_date, r.rating_comment, u.username 
                    FROM rating r 
                    JOIN users u ON r.user_id = u.user_id 
                    WHERE r.recipe_id = $recipe_id";
    $result_ratings = $conn->query($sql_ratings);
    $ratings = [];
    if ($result_ratings->num_rows > 0) {
        while ($row = $result_ratings->fetch_assoc()) {
            $ratings[] = $row;
        }
    }

    // Calculate average rating
    $sql_avg_rating = "SELECT AVG(rating) as avg_rating FROM rating WHERE recipe_id = $recipe_id";
    $result_avg_rating = $conn->query($sql_avg_rating);
    $avg_rating = 0;
    if ($result_avg_rating->num_rows > 0) {
        $row_avg = $result_avg_rating->fetch_assoc();
        $avg_rating = round($row_avg['avg_rating'], 2);
    }

    // Handle rating and comment submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['rating'])) {
        $user_id = $_SESSION['user_id'];  // Assuming user is logged in and user_id is stored in session
        $rating = intval($_POST['rating']);
        $rating_comment = $conn->real_escape_string($_POST['rating_comment']); // Escape comment for security

        // Check if user has already rated this recipe
        $sql_check = "SELECT * FROM rating WHERE user_id = $user_id AND recipe_id = $recipe_id";
        $result_check = $conn->query($sql_check);
        if ($result_check->num_rows > 0) {
            // Update existing rating and comment
            $sql_update = "UPDATE rating SET rating = $rating, rating_comment = '$rating_comment', rating_date = NOW() WHERE user_id = $user_id AND recipe_id = $recipe_id";
            $conn->query($sql_update);
        } else {
            // Insert new rating and comment
            $sql_insert = "INSERT INTO rating (user_id, recipe_id, rating, rating_comment, rating_date) VALUES ($user_id, $recipe_id, $rating, '$rating_comment', NOW())";
            $conn->query($sql_insert);
        }

        // Refresh to show updated ratings
        header("Location: recipedetails.php?recipe_id=$recipe_id");
        exit();
    }
} else {
    echo "Recipe ID not provided";
    exit;
}

function getHomeLink($user_type)
{
    switch ($user_type) {
        case 'admin':
            return 'recipes.php';
        case 'nutritionist':
            return 'recipes.php';
        default:
            return 'user.php';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($recipe_name); ?></title>
    <link rel="stylesheet" href="../styling/recipedetails.css">
</head>

<body>
    <div class="taskbar">
        <div class="taskbar-left">
            <h1>NourishNet</h1>
        </div>
        <div class="taskbar-right">
            <a href="<?php echo getHomeLink(htmlspecialchars($user_type)); ?>"><i class="fas fa-home"></i></a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i></a>
        </div>
    </div>

    <div class="content">
        <h2><?php echo htmlspecialchars($recipe_name); ?></h2>
        <img class="recipe-image" src="<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($recipe_name); ?>"><br><br>
        
        <!-- Description Section -->
        <div class="description">
            <h3>Description:</h3>
            <p><?php echo htmlspecialchars($description); ?></p>
        </div>

        <!-- Ingredients Section -->
        <div class="ingredients">
            <h3>Ingredients:</h3>
            <ul>
                <?php foreach ($ingredients as $ingredient): ?>
                    <li><?php echo htmlspecialchars($ingredient); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Instructions Section -->
        <div class="instructions">
            <h3>Instructions:</h3>
            <ol>
                <?php foreach ($instructions as $instruction): ?>
                    <li><?php echo htmlspecialchars($instruction); ?></li>
                <?php endforeach; ?>
            </ol>
        </div>

        <!-- Recipe Type Section -->
        <div class="recipe-type">
            <h3>Recipe Type:</h3>
            <p><?php echo htmlspecialchars($tag); ?></p>
        </div>

        <!-- Nutritional Information -->
        <div class="nutrition">
            <h3>Nutritional Information:</h3>
            <ul>
                <?php foreach (explode("\n", $recipe_nutrition) as $nutrition_item): ?>
                    <li><?php echo htmlspecialchars($nutrition_item); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Recipe Suitability -->
        <div class="suitability">
            <h3>Recipe Suitability:</h3>
            <ul>
                <?php foreach ($recipe_suitability as $suitability_item): ?>
                    <li><?php echo htmlspecialchars($suitability_item); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Rating and Comment Form -->
        <div class="rating-form">
            <h3>Rate this Recipe:</h3>
            <form action="" method="post">
                <input type="hidden" name="recipe_id" value="<?php echo htmlspecialchars($recipe_id); ?>">
                <label for="rating">Rating:</label>
                <select name="rating" id="rating" required>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
                <br>
                <label for="rating_comment">Comment:</label><br>
                <textarea name="rating_comment" id="rating_comment" rows="4" cols="50"
                    placeholder="Leave a comment (optional)"></textarea><br>
                <input type="submit" value="Submit Rating">
            </form>
        </div>

        <!-- Display Ratings and Comments -->
        <div class="ratings">
            <h3>All Ratings (Average Rating: <?php echo $avg_rating; ?>)</h3>
            <?php foreach ($ratings as $rating): ?>
                <div class="rating">
                    <p class="username"><?php echo htmlspecialchars($rating['username']); ?></p>
                    <p>Rating: <?php echo htmlspecialchars($rating['rating']); ?></p>
                    <?php if (!empty($rating['rating_comment'])): ?>
                        <p>Comment: <?php echo htmlspecialchars($rating['rating_comment']); ?></p>
                    <?php endif; ?>
                    <p>Date: <?php echo htmlspecialchars($rating['rating_date']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Back to Feed Button -->
        <div class="back-to-feed">
            <a href="<?php echo getHomeLink(htmlspecialchars($user_type)); ?>">Back to Feed</a>
        </div>

    </div>
</body>

</html>
