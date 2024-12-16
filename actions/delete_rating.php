<?php
session_start();
require_once '../db/config.php';

// The code to ensure user is logged in
if (!isset($_SESSION['UserID'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$userId = $_SESSION['UserID'];

// The code to get restaurant name from POST
if (!isset($_POST['restaurantName'])) {
    echo json_encode(['success' => false, 'message' => 'Missing restaurant name']);
    exit;
}

$restaurantName = $_POST['restaurantName'];

// The code to delete the rating
$sql = "DELETE FROM DWB_Ratings
        WHERE UserID = ? AND RestaurantID = (
            SELECT RestaurantID FROM DWB_Restaurants WHERE ResName = ?
        )";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $userId, $restaurantName);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Rating deleted']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete rating']);
}

$stmt->close();
$conn->close();
?>