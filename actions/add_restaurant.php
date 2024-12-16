<?php
include '../db/config.php';
session_start();

if (!isset($_SESSION['UserID'])) {
    header('Location: ../view/login.php');
    exit;
}
error_reporting(E_ALL);
ini_set('display_errors', 1);


// The code to validate and process the form data
$restaurantName = $_POST['ResName'];
$address = $_POST['ResAddress'];
$phone = $_POST['PhoneNumber'];
$features = $_POST['AccessibilityFeatures'];
$userId = $_SESSION['UserID'];

// The code to handle image upload
$imagePath = null;
if (isset($_FILES['restaurantImage']) && $_FILES['restaurantImage']['error'] == 0) {
    $uploadDir = '../../uploads/';
    $imageName = uniqid() . '_' . basename($_FILES['restaurantImage']['name']);
    $imagePath = $uploadDir . $imageName;
    
    if (!move_uploaded_file($_FILES['restaurantImage']['tmp_name'], $imagePath)) {
        $_SESSION['error'] = "Failed to upload image";
        echo 'failed image';

        exit;
    }
}

// The code to prepare, insert query for pending restaurants table
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

// The code for the execute message
if ($stmt->execute()) {
    echo "Restaurant details submitted and awaiting admin approval";
} else {
    echo "Failed to submit restaurant details";
}

$stmt->close();
$conn->close();

header('Location: ../view/restaurant.php');
exit;
?>