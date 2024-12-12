<?php
session_start();
require_once '../db/config.php';

// Ensure user is logged in
if (!isset($_SESSION['UserID'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$userId = $_SESSION['UserID'];

// Fetch user profile information with all details
$sql = "SELECT UserID, FirstName, LastName, Email, UserType FROM DWB_Users WHERE UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    // Concatenate first name and last name
    $fullName = trim($user['FirstName'] . ' ' . $user['LastName']);
    
    echo json_encode([
        'success' => true,
        'UserID' => $user['UserID'],
        'fullName' => $fullName,
        'firstName' => $user['FirstName'],
        'lastName' => $user['LastName'],
        'email' => $user['Email'],
        'userType' => $user['UserType']
    ]);
} else {
    echo json_encode(['error' => 'User not found']);
}

$stmt->close();
$conn->close();
?>