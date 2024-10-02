<?php
session_start();

// Redirect to login if user is not authenticated
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

include 'db_connect.php'; // Database connection

$feed = ['recipes' => [], 'diets' => []];
$categories = [];

// Fetch recipes
$recipesQuery = "SELECT r.*, u.username, u.profile_image
                 FROM recipe r 
                 JOIN users u ON r.user_id = u.user_id 
                 ORDER BY r.date_created DESC"; // Adjusted to use `date_created`
$recipesResult = mysqli_query($conn, $recipesQuery);

if ($recipesResult) {
    while ($recipe = mysqli_fetch_assoc($recipesResult)) {
        $feed['recipes'][] = $recipe;
    }
} else {
    echo "Error fetching recipes: " . mysqli_error($conn);
}

// Fetch diets (foods) with categories
$dietsQuery = "SELECT d.*, u.username, u.profile_image, c.category_name
               FROM foods d 
               JOIN users u ON d.user_id = u.user_id 
               JOIN food_categories fc ON d.food_id = fc.food_id
               JOIN categories c ON fc.category_id = c.category_id
               ORDER BY d.created_at DESC"; // Adjusted to use `created_at` and join categories
$dietsResult = mysqli_query($conn, $dietsQuery);

if ($dietsResult) {
    while ($diet = mysqli_fetch_assoc($dietsResult)) {
        $feed['diets'][] = $diet;
    }
} else {
    echo "Error fetching diets: " . mysqli_error($conn);
}

// Fetch categories
$categoriesQuery = "SELECT * FROM categories";
$categoriesResult = mysqli_query($conn, $categoriesQuery);

if ($categoriesResult) {
    while ($category = mysqli_fetch_assoc($categoriesResult)) {
        $categories[] = $category;
    }
} else {
    echo "Error fetching categories: " . mysqli_error($conn);
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Feed</title>
    <link rel="stylesheet" href="../styling/user.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <header class="header">
        <a href="#" class="logo">NourishNet</a>
        <nav class="navbar">
            <a href="profile.php"><i class="fas fa-user"></i>profile</a>
            <a href="uploadrecipe.php"><i class="fas fa-cloud-upload-alt"></i>upload</a>
            <a href="userconsultation.php"><i class="fas fa-comments"></i>chat</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i>logout</a>
        </nav>
    </header>

    <!-- Toggle Buttons Container -->
    <div id="toggle-container">
        <div class="toggle-buttons">
            <button class="toggle-button active" onclick="showRecipes()">Recipes</button>
            <button class="toggle-button" onclick="showDiets()">Diets</button>
        </div>
    </div>

    <!-- Feed Container -->
    <div id="feed-container">
        <!-- Recipes Section -->
        <div id="recipes" class="feed-section">
            <?php if (!empty($feed['recipes'])): ?>
                <?php foreach ($feed['recipes'] as $recipe): ?>
                    <div class="feed-item">
                        <a href="profile.php?user_id=<?php echo $recipe['user_id']; ?>" class="profile-link">
                            <img src="<?php echo $recipe['profile_image'] ?? 'default_profile.png'; ?>" alt="Profile Image">
                            <span class="username"><?php echo $recipe['username']; ?></span>
                        </a>
                        <h3 class="item-title"><?php echo $recipe['recipe']; ?></h3>
                        <p class="item-description"><?php echo $recipe['recipe_description']; ?></p>
                        <p class="item-date">Created on: <?php echo date('F j, Y', strtotime($recipe['date_created'])); ?></p>
                        <?php if (!empty($recipe['recipe_image'])): ?>
                            <img src="<?php echo $recipe['recipe_image']; ?>" alt="Recipe Image" class="item-image">
                        <?php endif; ?>
                        <a href="recipedetails.php?recipe_id=<?php echo $recipe['recipe_id']; ?>" class="view-details-button">View Recipe Details</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No recipes found.</p>
            <?php endif; ?>
        </div>

        <!-- Diets Section (Initially Hidden) -->
        <div id="diets" class="feed-section" style="display: none;">
            <!-- Category Filter Dropdown -->
            <select id="category-filter" onchange="filterDiets()">
                <option value="all">All Categories</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['category_name']; ?>"><?php echo $category['category_name']; ?></option>
                <?php endforeach; ?>
            </select>
            
            <?php if (!empty($feed['diets'])): ?>
                <?php foreach ($feed['diets'] as $diet): ?>
                    <div class="feed-item diet-item" data-category="<?php echo $diet['category_name']; ?>">
                        <a href="profile.php?user_id=<?php echo $diet['user_id']; ?>" class="profile-link">
                            <img src="<?php echo $diet['profile_image'] ?? 'default_profile.png'; ?>" alt="Profile Image">
                            <span class="username"><?php echo $diet['username']; ?></span>
                        </a>
                        <h3 class="item-title"><?php echo $diet['food_name']; ?></h3>
                        <p class="item-description"><?php echo $diet['description']; ?></p>
                        <p class="item-category">Category: <?php echo $diet['category_name']; ?></p> <!-- Display category name -->
                        <p class="item-date">Created on: <?php echo date('F j, Y', strtotime($diet['created_at'])); ?></p>
                        <?php if (!empty($diet['image_url'])): ?>
                            <img src="<?php echo $diet['image_url']; ?>" alt="Food Image" class="item-image">
                        <?php endif; ?>
                        <a href="dietdetails.php?food_id=<?php echo $diet['food_id']; ?>" class="view-details-button">View Food Details</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No diets found.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- JavaScript code -->
    <script>
        // JavaScript functions for toggling sections
        function showRecipes() {
            document.getElementById('recipes').style.display = 'block';
            document.getElementById('diets').style.display = 'none';
            document.querySelector('.toggle-button.active').classList.remove('active');
            document.querySelectorAll('.toggle-button')[0].classList.add('active');
        }

        function showDiets() {
            document.getElementById('recipes').style.display = 'none';
            document.getElementById('diets').style.display = 'block';
            document.querySelector('.toggle-button.active').classList.remove('active');
            document.querySelectorAll('.toggle-button')[1].classList.add('active');
        }

        // Show recipes section by default
        showRecipes();

        // Function to filter diets based on category
        function filterDiets() {
            const filter = document.getElementById('category-filter').value;
            const items = document.querySelectorAll('.diet-item');
            
            items.forEach(item => {
                const category = item.getAttribute('data-category');
                if (filter === 'all' || category === filter) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }
    </script>
    <!-- Footer Section -->
    <footer class="footer">
        <p>&copy; 2024 NourishNet. All rights reserved.</p>
    </footer>
</body>

</html>
