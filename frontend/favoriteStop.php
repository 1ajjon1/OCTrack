<?php
session_start();

include "dbConnection.php";

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    echo json_encode(["status" => "error", "message" => "User not authenticated."]);
    exit();
}

// Get the JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Verify stop_number input
if (!isset($data['stop_number']) || empty($data['stop_number'])) {
    echo json_encode(["status" => "error", "message" => "Stop number is required."]);
    exit();
}

$stop_number = $data['stop_number'];
$username = $_SESSION['username']; // Logged-in username

// Connect to the database
$dbConnection = connect2db();

// Check if the stop already exists in the user's favorites
$query = "SELECT * FROM favorite_stops WHERE username = ? AND stop_number = ?";
$stmt = $dbConnection->prepare($query);

if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "Database query preparation failed."]);
    $dbConnection->close();
    exit();
}

$stmt->bind_param('ss', $username, $stop_number);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    // Stop already in favorites
    echo json_encode(["status" => "error", "message" => "Stop already in favorites."]);
} else {
    // Stop not found in favorites, proceed with adding it
    $insertQuery = "INSERT INTO favorite_stops (username, stop_number) VALUES (?, ?)";
    $insertStmt = $dbConnection->prepare($insertQuery);

    if (!$insertStmt) {
        echo json_encode(["status" => "error", "message" => "Database insert query preparation failed."]);
        $dbConnection->close();
        exit();
    }

    $insertStmt->bind_param('ss', $username, $stop_number);

    if ($insertStmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Stop added to favorites."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to add stop to favorites."]);
    }
}

$stmt->close();
$insertStmt->close();
$dbConnection->close();
?>
