<?php
include '../db/config.php';
session_start();

if (!isset($_SESSION['RestaurantID'])) {
    header('Location: ../view/login.php');
    exit;
}

$restaurantId = $_SESSION['RestaurantID'];

// Validate and process form data
$restaurantName = $_POST['ResName'];
$address = $_POST['ResAddress'];
$phone = $_POST['PhoneNumber'];
$features = $_POST['AccessibilityFeatures'];

// Handle image upload
$imagePath = null;
if (isset($_FILES['restaurantImage']) && $_FILES['restaurantImage']['error'] == 0) {
    $uploadDir = '../uploads/';
    $imageName = uniqid() . '_' . basename($_FILES['restaurantImage']['name']);
    $imagePath = $uploadDir . $imageName;
    
    if (move_uploaded_file($_FILES['restaurantImage']['tmp_name'], $imagePath)) {
        // Image uploaded successfully
    } else {
        // Handle upload error
        $_SESSION['error'] = "Failed to upload image";
        header('Location: ../view/restaurant.php');
        exit;
    }
}

// Prepare update query
$query = "UPDATE DWB_Restaurants SET
        ResName = ?,
        ResAddress = ?,
        PhoneNumber = ?,
        AccessibilityFeatures = ?" .
        ($imagePath ? ", RestaurantImage = ?" : "") .
        " WHERE RestaurantID = ?";

$stmt = $conn->prepare($query);

// Bind parameters dynamically
if ($imagePath) {
    $stmt->bind_param("sssssi",
        $restaurantName,
        $address,
        $phone,
        $features,
        $imagePath,
        $restaurantId
    );
} else {
    $stmt->bind_param("ssssi",
        $restaurantName,
        $address,
        $phone,
        $features,
        $restaurantId
    );
}

// Execute update
if ($stmt->execute()) {
    $_SESSION['success'] = "Restaurant profile updated successfully";
} else {
    $_SESSION['error'] = "Failed to update restaurant profile";
}

$stmt->close();
$conn->close();

header('Location: ../view/restaurant.php');
exit;