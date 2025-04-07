<?php
session_start(); 

include "dbConnection.php";

$data = json_decode(file_get_contents('php://input'), true);  // Get the JSON input from the request

$stop_number = $data['stop_number'];
$username = $_SESSION['username']; // Get the logged-in username

// Insert the stop into the favorites table
$query = "INSERT INTO favorite_stops (username, stop_number) VALUES (?, ?)";
$stmt = $dbConnection->prepare($query);
$stmt->bind_param('ss', $username, $stop_number);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}

$stmt->close();
$dbConnection->close();
?>
