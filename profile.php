<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: users.php");
    exit();
}

$logged_in_user_id = $_SESSION['user_id'];

// Check if a user_id is passed in the URL for viewing another user's profile
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : $logged_in_user_id;

// Fetch user details
$stmt = $conn->prepare("SELECT username, profile_image FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $profile_image);
$stmt->fetch();
$stmt->close();

// Fetch user recipes
$stmt = $conn->prepare("SELECT recipe_id, recipe, recipe_description, recipe_image FROM recipe WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($recipe_id, $recipe, $recipe_description, $recipe_image);

$items = [];
while ($stmt->fetch()) {
    $items[] = [
        'type' => 'recipe',
        'id' => $recipe_id,
        'name' => $recipe,
        'description' => $recipe_description,
        'image' => $recipe_image
    ];
}
$stmt->close();

// Fetch user diets
$stmt = $conn->prepare("SELECT food_id, food_name, description, image_url FROM foods WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($food_id, $food_name, $description, $image_url);

while ($stmt->fetch()) {
    $items[] = [
        'type' => 'food',
        'id' => $food_id,
        'name' => $food_name,
        'description' => $description,
        'image' => $image_url
    ];
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="../styling/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <div class="taskbar">
        <div class="taskbar-left">
            <h1>NourishNet</h1>
        </div>
        <div class="taskbar-right">
            <button onclick="location.href='user.php'"><i class="fas fa-home"></i>home</button>
            <button onclick="location.href='editusers.php?username=<?php echo $username; ?>'"><i class="fas fa-user-edit"></i>edit profile</button>
            <button onclick="location.href='logout.php'"><i class="fas fa-sign-out-alt"></i>logout</button>
        </div>
    </div>

    <div class="profile-container">
        <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Picture" class="profile-picture">
        <div class="profile-info">
            <h2><?php echo htmlspecialchars($username); ?></h2>
        </div>
    </div>

    <div class="recipe-container">
        <?php foreach ($items as $item): ?>
            <div class="recipe-item">
                <?php if ($item['image']): ?>
                    <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="Item Image">
                <?php else: ?>
                    <img src="default_item_image.jpg" alt="Default Item Image">
                <?php endif; ?>
                <div class="recipe-details">
                    <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                    <p><?php echo htmlspecialchars($item['description']); ?></p>
                    <button class="view-recipe-btn" onclick="location.href='<?php echo $item['type'] == 'recipe' ? 'recipedetails.php?recipe_id=' . $item['id'] : 'dietdetails.php?food_id=' . $item['id']; ?>'">View <?php echo ucfirst($item['type']); ?></button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="footer">
        &copy; 2024 NourishNet. All rights reserved.
    </div>
</body>

</html>
