<?php
require_once '../db/config.php';

//The code to ensure user is logged in and has a valid user ID
session_start();
$userId = isset($_SESSION['UserID']) ? $_SESSION['UserID'] : 1;

//The code to check if required parameters are set
if (isset($_POST['RestaurantID']) && isset($_POST['Rating'])) {
    $restaurantId = intval($_POST['RestaurantID']);
    $rating = intval($_POST['Rating']);

    //The code to validate rating is between 1 and 5
    if ($rating < 1 || $rating > 5) {
        echo json_encode(['success' => false, 'message' => 'Invalid rating']);
        exit;
    }

    //The code to prepare and execute the SQL statement
    $sql = "INSERT INTO DWB_Ratings (RestaurantID, UserID, Rating)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE Rating = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $restaurantId, $userId, $rating, $rating);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Rating saved']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save rating']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Missing parameters']);
}

$conn->close();
?>