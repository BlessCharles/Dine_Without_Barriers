<?php
session_start();
require_once '../db/config.php';

// The code to ensure user is logged in
if (!isset($_SESSION['UserID'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$userId = $_SESSION['UserID'];

// The code to check if required fields are sent
if (!isset($_POST['firstName']) || !isset($_POST['lastName']) || !isset($_POST['email'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

$firstName = trim($_POST['firstName']);
$lastName = trim($_POST['lastName']);
$email = trim($_POST['email']);

// The code for validation for the fields to not be empty
if (empty($firstName) || empty($lastName) || empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Fields cannot be empty']);
    exit;
}

// The code to update user profile
$sql = "UPDATE DWB_Users SET FirstName = ?, LastName = ?, Email = ? WHERE UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $firstName, $lastName, $email, $userId);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update profile']);
}

$stmt->close();
$conn->close();
?>