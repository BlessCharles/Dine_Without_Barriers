<?php

include '../db/config.php';
global $conn;

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

//The code to check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userID = $_POST['UserID'];
    $firstName = trim($_POST['FirstName']);
    $lastName = trim($_POST['LastName']);
    $userType = trim($_POST['UserType']);
    $email = trim($_POST['Email']);

    //The code to validate inputs
    if (empty($firstName) || empty($lastName) || empty($userType) || empty($email)) {
        echo '<script>alert("Please fill in all required fields.");</script>';
        echo '<script>window.history.back();</script>';
        exit;
    }

    //The code to prepare an SQL statement to update user data
    $stmt = $conn->prepare('UPDATE DWB_Users SET FirstName = ?, LastName = ?, UserType = ?, Email = ? WHERE UserID = ?');
    $stmt->bind_param('ssssi', $firstName, $lastName, $userType, $email, $userID);

    if ($stmt->execute()) {
        echo '<script>alert("User details updated successfully.");</script>';
        echo '<script>window.location.href = "../view/users.php";</script>';
    } else {
        echo '<script>alert("Failed to update user details. Please try again.");</script>';
        echo '<script>window.history.back();</script>';
    }

    $stmt->close();
}

$conn->close();
?>
