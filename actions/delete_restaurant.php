<?php
include '../db/config.php';
session_start();
//The code to ensure user is logged in

if (!isset($_SESSION['RestaurantID'])) {
    header('Location: ../view/login.php');
    exit;
}

$restaurantId = $_SESSION['RestaurantID'];

// The code to prepare delete query
$query = "DELETE FROM DWB_Restaurants WHERE RestaurantID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $restaurantId);

// The code to execute delete
if ($stmt->execute()) {
    // The code to clear session and redirect to login
    session_destroy();
    header('Location: ../view/login.php');
} else {
    $_SESSION['error'] = "Failed to delete restaurant account";
    header('Location: ../view/restaurant.php');
}

$stmt->close();
$conn->close();
exit;