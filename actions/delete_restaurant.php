<?php
include '../db/config.php';
session_start();

if (!isset($_SESSION['RestaurantID'])) {
    header('Location: ../view/login.php');
    exit;
}

$restaurantId = $_SESSION['RestaurantID'];

// Prepare delete query
$query = "DELETE FROM DWB_Restaurants WHERE RestaurantID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $restaurantId);

// Execute delete
if ($stmt->execute()) {
    // Clear session and redirect to login
    session_destroy();
    header('Location: ../view/login.php');
} else {
    $_SESSION['error'] = "Failed to delete restaurant account";
    header('Location: ../view/restaurant.php');
}

$stmt->close();
$conn->close();
exit;