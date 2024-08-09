<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "robot_control";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the last movement
$sql = "SELECT direction FROM movements ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

$last_movement = "No movements recorded yet";

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $last_movement = $row['direction'];
}

$conn->close();

echo $last_movement;
?>