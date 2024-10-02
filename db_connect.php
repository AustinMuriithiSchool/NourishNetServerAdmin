<?php
$servername = "102.37.157.159";
$username = "root";  // Your MySQL root username
$password = "";  // Your root password
$dbname = "CAT1";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>
