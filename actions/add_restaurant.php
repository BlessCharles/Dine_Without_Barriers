<?php
include '../db/config.php';
session_start();

if (!isset($_SESSION['UserID'])) {
    header('Location: ../view/login.php');
    exit;
}

// Validate and process form data
$restaurantName = $_POST['ResName'];
$address = $_POST['ResAddress'];
$phone = $_POST['PhoneNumber'];
$features = $_POST['AccessibilityFeatures'];
$userId = $_SESSION['UserID'];

// Handle image upload
$imagePath = null;
if (isset($_FILES['restaurantImage']) && $_FILES['restaurantImage']['error'] == 0) {
    $uploadDir = '../uploads/';
    $imageName = uniqid() . '_' . basename($_FILES['restaurantImage']['name']);
    $imagePath = $uploadDir . $imageName;
    
    if (!move_uploaded_file($_FILES['restaurantImage']['tmp_name'], $imagePath)) {
        $_SESSION['error'] = "Failed to upload image";
        header('Location: ../view/restaurant.php');
        exit;
    }
}

// Prepare insert query for pending restaurants table
$query = "INSERT INTO DWB_Restaurant_Pending
        (UserID, ResName, ResAddress, PhoneNumber, AccessibilityFeatures, RestaurantImage)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($query);
$stmt->bind_param("isssss",
    $userId,
    $restaurantName,
    $address,
    $phone,
    $features,
    $imagePath
);

// Execute insert
if ($stmt->execute()) {
    $_SESSION['success'] = "Restaurant details submitted and awaiting admin approval";
} else {
    $_SESSION['error'] = "Failed to submit restaurant details";
}

$stmt->close();
$conn->close();

header('Location: ../view/restaurant.php');
exit;
?>