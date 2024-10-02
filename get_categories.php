<?php
session_start();

// Redirect to login if user is not authenticated
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}
// Include database connection
include('db_connect.php');

// Fetch categories from the database
$sql = "SELECT category_id, category_name FROM categories";
$result = $conn->query($sql);

$categories = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Close database connection
$conn->close();

// Return categories as JSON
header('Content-Type: application/json');
echo json_encode($categories);

