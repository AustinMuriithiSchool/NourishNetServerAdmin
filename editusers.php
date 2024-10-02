<?php
session_start();
include 'db_connect.php'; // Include your database connection script

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html"); // Redirect to login page if not logged in
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input fields
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
    $profile_image = '';

    // Handle profile picture upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
        $file_extension = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);

        if (in_array(strtolower($file_extension), $allowed_types)) {
            $target_dir = "profilepictures/";
            $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);

            // Move uploaded file to target directory
            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                $profile_image = $target_file;
            } else {
                die("Error uploading profile picture.");
            }
        } else {
            die("Invalid file type for profile picture.");
        }
    }

    // Validate input fields
    if (empty($username) || empty($email)) {
        die("Please fill in all fields.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    // Prepare SQL statement for updating user profile
    $query = "UPDATE users SET username=?, email=?";
    $params = array($username, $email);

    if ($password) {
        $query .= ", password=?";
        $params[] = $password;
    }

    if ($profile_image) {
        $query .= ", profile_image=?";
        $params[] = $profile_image;
    }

    $query .= " WHERE user_id=?";
    $params[] = $user_id;

    // Execute the update query
    $stmt = $conn->prepare($query);
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);

    if ($stmt->execute()) {
        header("Location: user.php"); // Redirect to nutrition.php after successful update
        exit();
    } else {
        echo "Error updating profile: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch current user data for pre-filling the form fields
$stmt = $conn->prepare("SELECT username, email, profile_image FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email, $profile_image);
$stmt->fetch();
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            margin-top: 70px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        .edit-box {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        .textbox {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="file"] {
            margin-bottom: 10px;
        }

        input[type="submit"] {
            background: #29d978;
            color: white;
            border: none;
            padding: 10px 20px;
            margin-top: 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        input[type="submit"]:hover {
            background: #25c167;
        }

        .profile-picture {
            display: block;
            margin: 0 auto 20px;
            max-width: 100px;
            border-radius: 50%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="edit-box">
            <h2>Edit Profile</h2>
            <form method="post" action="editusers.php" enctype="multipart/form-data">
                <div class="textbox">
                    <label for="profile_image">Profile Picture:</label>
                    <input type="file" id="profile_image" name="profile_image" accept="image/*">
                </div>
                <?php if (!empty($profile_image)): ?>
                    <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Picture"
                        class="profile-picture">
                <?php endif; ?>
                <div class="textbox">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>">
                </div>
                <div class="textbox">
                    <label for="email">Email:</label>
                    <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                </div>
                <div class="textbox">
                    <label for="password">New Password (leave blank to keep current):</label>
                    <input type="password" id="password" name="password">
                </div>

                <input type="submit" class="btn" value="Update">
            </form>
        </div>
    </div>
</body>

</html>