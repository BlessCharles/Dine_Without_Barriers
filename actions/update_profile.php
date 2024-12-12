<?php
// Connect to the database
include '../db/config.php';
global $conn;

// Start session
session_start();

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userID = $_POST['UserID'];
    $firstName = trim($_POST['FirstName']);
    $lastName = trim($_POST['LastName']);
    $userType = trim($_POST['UserType']);
    $email = trim($_POST['Email']);

    // Validate inputs
    if (empty($firstName) || empty($lastName) || empty($userType) || empty($email)) {
        echo '<script>alert("Please fill in all required fields.");</script>';
        echo '<script>window.history.back();</script>';
        exit;
    }

    // Prepare an SQL statement to update user data
    $stmt = $conn->prepare('UPDATE DWB_Users SET FirstName = ?, LastName = ?, UserType = ?, Email = ? WHERE UserID = ?');
    $stmt->bind_param('ssssi', $firstName, $lastName, $userType, $email, $userID);

    if ($stmt->execute()) {
        echo '<script>alert("User details updated successfully.");</script>';
        echo '<script>window.location.href = "../view/wheelchair_dashboard.php";</script>';
    } else {
        echo '<script>alert("Failed to update user details. Please try again.");</script>';
        echo '<script>window.history.back();</script>';
    }

    $stmt->close();
}

$conn->close();
?>