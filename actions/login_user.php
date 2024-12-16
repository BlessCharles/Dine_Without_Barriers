<?php
//The code to connect to the database
include '../db/config.php';
global $conn;


error_reporting(E_ALL);
ini_set('display_errors', 1);

//The code to start a session
session_start();

//The code to check if the form was submitted using POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //The code to collect and trim form data
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    //The code to check if required fields are empty
    if (empty($email) || empty($password)) {
        die('Please fill in all required fields.');
    }

    //The code to prepare statement to check if the email exists in the database
    $stmt = $conn->prepare('SELECT UserID, FirstName, Email, UserPassword, UserType FROM DWB_Users WHERE Email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $results = $stmt->get_result();

    //The code to check if the email exists
    if ($results->num_rows > 0) {
        $user = $results->fetch_assoc();

        //The code to verify the password
        if (password_verify($password, $user['UserPassword'])) {

            //The code to set session variables
            $_SESSION['UserID'] = $user['UserID'];
            $_SESSION['FirstName'] = $user['FirstName'];
            $_SESSION['Email'] = $user['Email'];
            $_SESSION['UserType'] = $user['UserType'];

            //The code to redirect to the appropriate dashboard
            if ($user['UserType'] === 'Restaurant') {
                header('Location: ../view/restaurant_page.php');
            } elseif ($user['UserType'] === 'WheelchairUser') {
                header('Location: ../view/wheelchair_user.php');
            } else {
                header('Location: ../view/dashboard.php');
            }
            exit;
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
