<?php
session_start();
include 'db_connect.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: index.html");
    exit();
}

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch total users
$totalUsersQuery = "SELECT COUNT(*) as total_users FROM users";
$totalUsersResult = $conn->query($totalUsersQuery);
$totalUsersRow = $totalUsersResult->fetch_assoc();
$totalUsers = $totalUsersRow['total_users'];

// Fetch total recipes
$totalRecipesQuery = "SELECT COUNT(*) as total_recipes FROM recipe";
$totalRecipesResult = $conn->query($totalRecipesQuery);
$totalRecipesRow = $totalRecipesResult->fetch_assoc();
$totalRecipes = $totalRecipesRow['total_recipes'];

// Fetch currently logged-in users (assuming you have a session table or similar mechanism)
$loggedInUsers = 0;
if (isset($_SESSION['logged_in_users'])) {
    $loggedInUsers = count($_SESSION['logged_in_users']);
}

// Fetch highest rated recipe
$highestRatedRecipeQuery = "
    SELECT r.recipe_id, r.recipe, AVG(rt.rating) as avg_rating
    FROM recipe r
    JOIN rating rt ON r.recipe_id = rt.recipe_id
    GROUP BY r.recipe_id
    ORDER BY avg_rating DESC
    LIMIT 1";
$highestRatedRecipeResult = $conn->query($highestRatedRecipeQuery);
$highestRatedRecipe = $highestRatedRecipeResult->fetch_assoc();

// Fetch lowest rated recipe
$lowestRatedRecipeQuery = "
    SELECT r.recipe_id, r.recipe, AVG(rt.rating) as avg_rating
    FROM recipe r
    JOIN rating rt ON r.recipe_id = rt.recipe_id
    GROUP BY r.recipe_id
    ORDER BY avg_rating ASC
    LIMIT 1";
$lowestRatedRecipeResult = $conn->query($lowestRatedRecipeQuery);
$lowestRatedRecipe = $lowestRatedRecipeResult->fetch_assoc();

// Fetch recipe tag counts
$recipeTagCountsQuery = "
    SELECT recipe_tag, COUNT(*) as tag_count
    FROM recipe
    GROUP BY recipe_tag
    ORDER BY tag_count DESC";
$recipeTagCountsResult = $conn->query($recipeTagCountsQuery);
$recipeTagCounts = [];
while ($row = $recipeTagCountsResult->fetch_assoc()) {
    $recipeTagCounts[] = $row;
}

// Fetch user type counts
$userTypeCountsQuery = "
    SELECT user_type, COUNT(*) as type_count
    FROM users
    GROUP BY user_type";
$userTypeCountsResult = $conn->query($userTypeCountsQuery);
$userTypeCounts = [];
while ($row = $userTypeCountsResult->fetch_assoc()) {
    $userTypeCounts[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics</title>
    <link rel="stylesheet" href="../styling/analytics.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="taskbar">
        <div class="taskbar-left">
            <h1>NourishNet</h1>
        </div>
        <div class="taskbar-right">
            <a href="admin.php"><i class="fas fa-home"></i>home</a>
            <a href="logout.php"><i class="fas fa-sign-out"></i>logout</a>
        </div>
    </div>
    <div class="container">
        <h2>Analytics</h2>
        <div class="stats">
            <div>Total Users: <?php echo $totalUsers; ?></div>
            <div>Total Recipes: <?php echo $totalRecipes; ?></div>
            <div>Highest Rated Recipe: <?php echo $highestRatedRecipe['recipe']; ?> (Rating:
                <?php echo round($highestRatedRecipe['avg_rating'], 2); ?>)</div>
            <div>Lowest Rated Recipe: <?php echo $lowestRatedRecipe['recipe']; ?> (Rating:
                <?php echo round($lowestRatedRecipe['avg_rating'], 2); ?>)</div>
        </div>
        <div class="chart-container">
            <div class="chart-title">Highest and Lowest Rated Recipes</div>
            <canvas id="barChart"></canvas>
        </div>
        <div class="chart-container">
            <div class="chart-title">Recipe Tag Distribution</div>
            <canvas id="recipeTagChart"></canvas>
        </div>
        <div class="chart-container">
            <div class="chart-title">User Type Distribution</div>
            <canvas id="userTypeChart"></canvas>
        </div>
    </div>
    <script>
        const highestRatedRecipe = '<?php echo $highestRatedRecipe['recipe']; ?>';
        const highestRatedRecipeRating = <?php echo round($highestRatedRecipe['avg_rating'], 2); ?>;
        const lowestRatedRecipe = '<?php echo $lowestRatedRecipe['recipe']; ?>';
        const lowestRatedRecipeRating = <?php echo round($lowestRatedRecipe['avg_rating'], 2); ?>;
        const recipeTagCounts = <?php echo json_encode($recipeTagCounts); ?>;
        const userTypeCounts = <?php echo json_encode($userTypeCounts); ?>;

        // Bar chart for highest and lowest rated recipes
        const barData = {
            labels: ['Highest Rated Recipe', 'Lowest Rated Recipe'],
            datasets: [{
                label: 'Rating',
                data: [highestRatedRecipeRating, lowestRatedRecipeRating],
                backgroundColor: ['#4CAF50', '#F44336'],
                hoverBackgroundColor: ['#45a049', '#e57373']
            }]
        };

        const barConfig = {
            type: 'bar',
            data: barData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 5
                    }
                }
            }
        };

        // Doughnut chart for recipe tag distribution
        const recipeTagCtx = document.getElementById('recipeTagChart').getContext('2d');
        const recipeTagChartConfig = {
            type: 'doughnut',
            data: {
                labels: recipeTagCounts.map(tag => tag.recipe_tag),
                datasets: [{
                    label: 'Recipe Tag Distribution',
                    data: recipeTagCounts.map(tag => tag.tag_count),
                    backgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#8e5ea2',
                        '#3cba9f',
                        '#e8c3b9',
                        '#c45850',
                        '#7d3c98',
                        '#4b5f71',
                        '#d73e68'
                    ],
                    hoverBackgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#8e5ea2',
                        '#3cba9f',
                        '#e8c3b9',
                        '#c45850',
                        '#7d3c98',
                        '#4b5f71',
                        '#d73e68'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function (tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw.toLocaleString();
                            }
                        }
                    }
                }
            }
        };

        // Bar chart for user type distribution
        const userTypeCtx = document.getElementById('userTypeChart').getContext('2d');
        const userTypeChartConfig = {
            type: 'bar',
            data: {
                labels: userTypeCounts.map(type => type.user_type),
                datasets: [{
                    label: 'User Type Distribution',
                    data: userTypeCounts.map(type => type.type_count),
                    backgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#8e5ea2',
                        '#3cba9f',
                        '#e8c3b9',
                        '#c45850',
                        '#7d3c98',
                        '#4b5f71',
                        '#d73e68'
                    ],
                    hoverBackgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#8e5ea2',
                        '#3cba9f',
                        '#e8c3b9',
                        '#c45850',
                        '#7d3c98',
                        '#4b5f71',
                        '#d73e68'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function (tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        };

        window.onload = function () {
            new Chart(document.getElementById('barChart').getContext('2d'), barConfig);
            new Chart(recipeTagCtx, recipeTagChartConfig);
            new Chart(userTypeCtx, userTypeChartConfig);
        };
    </script>
</body>

</html>