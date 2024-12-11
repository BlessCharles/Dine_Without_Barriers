<?php
include '../db/config.php';

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the form data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Collect and trim form data
    $fname = trim($_POST['firstname']);
    $lname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirmPassword']);
    $isRestaurant = isset($_POST['restaurantQuestion']) && $_POST['restaurantQuestion'] === 'yes';
    $isWheelchairUser = isset($_POST['wheelchairUser']) && $_POST['wheelchairUser'] === 'yes';

    // Validate required fields
    if (empty($fname) || empty($lname) || empty($email) || empty($password) || empty($confirm_password)) {
        die('Please fill in all required fields.');
    }

    // Validate passwords
    if ($password !== $confirm_password) {
        die('Passwords do not match.');
    }

    // Check if the email already exists
    $stmt = $conn->prepare('SELECT Email FROM DWB_Users WHERE Email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $results = $stmt->get_result();

    if ($results->num_rows > 0) {
        echo '<script>alert("User already registered");</script>';
        echo '<script>window.location.href="register.php";</script>';
        exit;
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Determine the user type based on their responses
        $userType = $isRestaurant ? 'Restaurant' : ($isWheelchairUser ? 'WheelchairUser' : 'GeneralUser');

        // Insert the new user into the database
        $query = 'INSERT INTO DWB_Users (FirstName, LastName, Email, UserPassword, UserType, CreatedAt) VALUES (?, ?, ?, ?, ?, NOW())';
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sssss', $fname, $lname, $email, $hashed_password, $userType);

        if ($stmt->execute()) {
            echo '<script>alert("Registration successful!");</script>';
            echo '<script>window.location.href="../view/login.php";</script>';
        } else {
            echo '<script>alert("Error occurred. Please try again.");</script>';
            echo '<script>window.location.href="../view/register.php";</script>';
        }
    }
    $stmt->close();
}

$conn->close();
?>
