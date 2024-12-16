<?php
session_start();
require_once '../db/config.php';

//The code to ensure user is logged in
if (!isset($_SESSION['UserID'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$userId = $_SESSION['UserID'];

//The code to fetch user profile information with concatenated full name
$sql = "SELECT FirstName, LastName, Email FROM DWB_Users WHERE UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    //The code to concatenate first name and last name
    $fullName = trim($user['FirstName'] . ' ' . $user['LastName']);
    
    echo json_encode([
        'success' => true,
        'fullName' => $fullName,
        'email' => $user['Email']
    ]);
} else {
    echo json_encode(['error' => 'User not found']);
}

$stmt->close();
$conn->close();
?>