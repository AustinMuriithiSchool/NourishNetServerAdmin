<?php
session_start();

// Redirect to login if user is not authenticated
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

// Include database connection
include 'db_connect.php';

// Establish database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve user role from session
$user_role = $_SESSION['user_type'] ?? 'user';

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
    <title>View Uploaded Foods</title>
    <link rel="stylesheet" href="../styling/diet.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <header class="header">
        <a href="#" class="logo">NourishNet</a>
        <nav class="navbar">
            <a href="<?php echo getHomeLink(htmlspecialchars($user_role)); ?>"><i class="fas fa-home"></i>home</a>
            <?php if ($user_role === 'nutritionist'): ?>
                <a href="upload.php"><i class="fas fa-cloud-upload-alt"></i>upload</a>
                <a href="consultation.php"><i class="fas fa-comments"></i>chat</a>
            <?php endif; ?>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i>logout</a>
        </nav>

    </header>

    <section class="foods">
        <h2 class="heading">Uploaded Foods</h2>
        <div class="foods-list">
            <?php
            // Fetch foods query
            $sql = "SELECT f.food_id, f.food_name, f.description, f.image_url, nv.calories, nv.protein, nv.fat, nv.carbohydrates, f.recipe, c.category_name, u.username
                    FROM foods f
                    INNER JOIN nutritional_values nv ON f.food_id = nv.food_id
                    INNER JOIN food_categories fc ON f.food_id = fc.food_id
                    INNER JOIN categories c ON fc.category_id = c.category_id
                    INNER JOIN users u ON f.user_id = u.user_id
                    ORDER BY f.food_id DESC";

            $result = $conn->query($sql);

            // Check if there are results
            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='food-item'>";
                    echo "<img src='" . htmlspecialchars($row["image_url"]) . "' alt='" . htmlspecialchars($row["food_name"]) . "'>";
                    echo "<h3>" . htmlspecialchars($row["food_name"]) . "</h3>";
                    echo "<p><strong>Uploaded by:</strong> " . htmlspecialchars($row["username"]) . "</p>";
                    echo "<p><strong>Description:</strong> " . htmlspecialchars($row["description"]) . "</p>";
                    echo "<p><strong>Calories:</strong> " . htmlspecialchars($row["calories"]) . "</p>";
                    echo "<p><strong>Protein:</strong> " . htmlspecialchars($row["protein"]) . "g</p>";
                    echo "<p><strong>Fat:</strong> " . htmlspecialchars($row["fat"]) . "g</p>";
                    echo "<p><strong>Carbohydrates:</strong> " . htmlspecialchars($row["carbohydrates"]) . "g</p>";
                    echo "<p><strong>Recipe:</strong> " . htmlspecialchars($row["recipe"]) . "</p>";
                    echo "<p><strong>Category:</strong> " . htmlspecialchars($row["category_name"]) . "</p>";
                    echo "<a href='dietdetails.php?food_id=" . htmlspecialchars($row["food_id"]) . "'>View Details</a>";
                    echo "</div>";
                }
            } else {
                echo "<p>No foods uploaded yet.</p>";
            }

            // Close database connection
            $conn->close();
            ?>
        </div>
    </section>

    <footer class="footer">
        <p>&copy; 2024 Nutritionist. All rights reserved.</p>
    </footer>
</body>

</html>
