<?php
session_start(); // Start the session

// Redirect to login if user is not authenticated
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

include('db_connect.php'); // Include your database connection

$user_id = $_SESSION['user_id'];
$selected_nutritionist_id = isset($_POST['nutritionist_id']) ? $_POST['nutritionist_id'] : '';

// Fetch nutritionists for selection
$nutritionists_result = $conn->query("
    SELECT user_id, username
    FROM users
    WHERE user_type = 'nutritionist'
");

// Handle form submission to start a consultation or send a message
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'])) {
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';

    // Validate nutritionist selection and message
    if (!empty($selected_nutritionist_id) && !empty($message)) {
        // Check if there's an existing consultation between the logged-in user and the selected nutritionist
        $stmt = $conn->prepare("SELECT consultation_id FROM consultations WHERE (nutritionist_id = ? AND user_id = ?) OR (nutritionist_id = ? AND user_id = ?)");
        $stmt->bind_param("iiii", $selected_nutritionist_id, $user_id, $user_id, $selected_nutritionist_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 0) {
            // No existing consultation, user is initiating the conversation
            $stmt = $conn->prepare("INSERT INTO consultations (nutritionist_id, user_id, consultation_date, notes, consultation_datetime) VALUES (?, ?, CURDATE(), ?, NOW())");
            $stmt->bind_param("iis", $selected_nutritionist_id, $user_id, $message);
            $stmt->execute();
            $consultation_id = $stmt->insert_id;
            $stmt->close();
        } else {
            // Existing consultation found, determine if it's a user or nutritionist message
            $row = $result->fetch_assoc();
            $consultation_id = $row['consultation_id'];
            $stmt = $conn->prepare("INSERT INTO consultation_replies (consultation_id, user_id, reply_date, reply_text) VALUES (?, ?, NOW(), ?)");
            $stmt->bind_param("iis", $consultation_id, $user_id, $message);
            $stmt->execute();
            $stmt->close();
        }
    } else {
        // Handle case where message is empty
        $error_message = "Message cannot be blank.";
    }
}

// Fetch all messages related to the selected nutritionist
$messages = [];
$usernames = [];
if (!empty($selected_nutritionist_id)) {
    // Fetch usernames for logged-in user and selected nutritionist
    $stmt = $conn->prepare("SELECT user_id, username FROM users WHERE user_id = ? OR user_id = ?");
    $stmt->bind_param("ii", $user_id, $selected_nutritionist_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $usernames[$row['user_id']] = $row['username'];
    }
    $stmt->close();

    // Fetch messages from consultations table
    $stmt = $conn->prepare("SELECT consultation_id, consultation_datetime as message_date, notes as message_text, nutritionist_id as sender_id FROM consultations WHERE (user_id = ? AND nutritionist_id = ?) OR (user_id = ? AND nutritionist_id = ?)");
    $stmt->bind_param("iiii", $user_id, $selected_nutritionist_id, $selected_nutritionist_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $row['sender_username'] = $usernames[$row['sender_id']];
        $row['sender_type'] = $row['sender_id'] == $user_id ? 'user' : 'nutritionist';
        $messages[] = $row;
    }
    $stmt->close();

    // Fetch messages from consultation_replies table
    $stmt = $conn->prepare("SELECT consultation_id, reply_date as message_date, reply_text as message_text, user_id as sender_id FROM consultation_replies WHERE consultation_id IN (SELECT consultation_id FROM consultations WHERE (user_id = ? AND nutritionist_id = ?) OR (user_id = ? AND nutritionist_id = ?))");
    $stmt->bind_param("iiii", $user_id, $selected_nutritionist_id, $selected_nutritionist_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $row['sender_username'] = $usernames[$row['sender_id']];
        $row['sender_type'] = $row['sender_id'] == $user_id ? 'user' : 'nutritionist';
        $messages[] = $row;
    }
    $stmt->close();

    // Sort messages by date
    usort($messages, function($a, $b) {
        return strtotime($a['message_date']) - strtotime($b['message_date']);
    });
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Start Consultation</title>
    <link rel="stylesheet" href="../styling/consultation.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        /* Additional CSS for message form */
        .message-form {
            position: relative;
        }

        .message-form textarea {
            width: calc(100% - 120px); /* Adjust the width of the textarea */
            margin-right: 10px;
        }

        .message-form button {
            position: absolute;
            bottom: 10px;
            right: 10px;
        }

        .message-box.user {
            background-color: #f1f0f0;
            text-align: right;
        }

        .message-box.nutritionist {
            background-color: #e0f7fa;
            text-align: left;
        }

        .message-box {
            padding: 10px;
            margin: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <header class="header">
        <a href="#" class="logo">NourishNet</a>
        <nav class="navbar">
            <a href="user.php"><i class="fas fa-home"></i> Home</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>
    </header>

    <section class="consultation">
        <h2> Consultation</h2>

        <!-- Form to select nutritionist -->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <label for="nutritionist_id">Select Nutritionist:</label>
            <select id="nutritionist_id" name="nutritionist_id" onchange="this.form.submit()">
                <option value="">Select a nutritionist...</option>
                <?php while ($nutritionist = $nutritionists_result->fetch_assoc()) { ?>
                    <option value="<?php echo $nutritionist['user_id']; ?>" <?php if ($nutritionist['user_id'] == $selected_nutritionist_id) echo 'selected'; ?>><?php echo $nutritionist['username']; ?></option>
                <?php } ?>
            </select>
        </form>

        <!-- Display messages -->
        <?php if (!empty($messages)) { ?>
            <h3>Conversation:</h3>
            <?php foreach ($messages as $msg) { ?>
                <div class="message-box <?php echo $msg['sender_type']; ?>">
                    <p><strong><?php echo $msg['sender_username']; ?>:</strong> <?php echo $msg['message_text']; ?></p>
                    <p><small><?php echo $msg['message_date']; ?></small></p>
                </div>
            <?php } ?>
        <?php } ?>

        <!-- Form to send message -->
        <?php if (!empty($selected_nutritionist_id)) { ?>
            <form class="message-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <input type="hidden" name="nutritionist_id" value="<?php echo $selected_nutritionist_id; ?>">
                <label for="message">Message:</label><br>
                <textarea id="message" name="message" rows="5" cols="40" placeholder="Enter your message..."></textarea><br><br>
                <?php if (isset($error_message)) { echo "<p style='color:red;'>$error_message</p>"; } ?>
                <button type="submit">Send Message</button>
            </form>
        <?php } ?>
    </section>

    <footer class="footer">
        <p>&copy; 2024 NourishNet. All rights reserved.</p>
    </footer>
</body>
</html>
