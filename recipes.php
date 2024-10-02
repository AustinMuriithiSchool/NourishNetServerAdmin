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

// Retrieve all recipes from the database
$sql = "SELECT recipe_id, recipe, recipe_image, recipe_description FROM recipe";
$result = $conn->query($sql);
$recipes = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $recipes[] = $row;
    }
} else {
    echo "No recipes found";
    exit;
}

// Retrieve user role from session
$user_type = $_SESSION['user_type'] ?? 'user';

// Function to get home link based on user role
function getHomeLink($user_type)
{
    switch ($user_type) {
        case 'admin':
            return 'admin.php';
        case 'nutritionist':
            return 'nutrition.php';
        default:
            return 'user.php';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Recipes</title>
    <link rel="stylesheet" href="../styling/recipe.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <header class="header">
        <div class="logo">NourishNet</div>
        <nav class="navbar">
            <a href="<?php echo getHomeLink(htmlspecialchars($user_type)); ?>"><i class="fas fa-home"></i>Home</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i>logout</a>
        </nav>
    </header>

    <section>
        <h2 class="heading"> Recipes</h2>
        <div class="foods-list">
            <?php foreach ($recipes as $recipe): ?>
                <div class="food-item">
                    <h3><?php echo htmlspecialchars($recipe['recipe']); ?></h3>
                    <img src="<?php echo htmlspecialchars($recipe['recipe_image']); ?>"
                        alt="<?php echo htmlspecialchars($recipe['recipe']); ?>">
                    <p><?php echo htmlspecialchars($recipe['recipe_description']); ?></p>
                    <a href="recipedetails.php?recipe_id=<?php echo htmlspecialchars($recipe['recipe_id']); ?>">View Recipe</a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <footer class="footer">
        &copy; 2024 NourishNet. All rights reserved.
    </footer>
</body>

</html>
