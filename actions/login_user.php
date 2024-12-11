<?php
// Connect to the database
include '../db/config.php';
global $conn;

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
session_start();

// Check if the form was submitted using POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Collect and trim form data
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Check if required fields are empty
    if (empty($email) || empty($password)) {
        die('Please fill in all required fields.');
    }

    // Prepare statement to check if the email exists in the database
    $stmt = $conn->prepare('SELECT UserID, FirstName, Email, UserPassword, UserType FROM DWB_Users WHERE Email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $results = $stmt->get_result();

    // Check if the email exists
    if ($results->num_rows > 0) {
        $user = $results->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['UserPassword'])) {

            // Set session variables
            $_SESSION['UserID'] = $user['UserID'];
            $_SESSION['FirstName'] = $user['FirstName'];
            $_SESSION['Email'] = $user['Email'];
            $_SESSION['UserType'] = $user['UserType'];

            // Redirect to the appropriate dashboard
            if ($user['UserType'] === 'Restaurant') {
                header('Location: ../view/restaurant_page.php');
            } elseif ($user['UserType'] === 'WheelchairUser') {
                header('Location: ../view/wheelchair_user.php');
            } else {
                header('Location: ../view/dashboard.php');
            }
            exit; // Ensure the script stops after redirection
        } else {
            echo '<script>alert("Incorrect password. Please try again.");</script>';
            echo '<script>window.location.href = "../view/login.php";</script>';
        }
    } else {
        echo '<script>alert("Email not registered. Please sign up.");</script>';
        echo '<script>window.location.href = "../view/register.php";</script>';
    }

    $stmt->close();
}

$conn->close();
?>
