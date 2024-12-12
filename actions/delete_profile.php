<?php
// The code to connect to the database
include '../db/config.php';
session_start();

// The code to check if the delete action is triggered
if (isset($_SESSION['UserID'])) {
    $user_id = $_SESSION['UserID'];

    // Start a transaction
    $conn->begin_transaction();

    try {
        // First, delete related records in other tables if necessary
        // For example, delete user's ratings
        $stmt1 = $conn->prepare("DELETE FROM DWB_Ratings WHERE UserID = ?");
        $stmt1->bind_param("i", $user_id);
        $stmt1->execute();
        $stmt1->close();

        // Then delete the user
        $stmt2 = $conn->prepare("DELETE FROM DWB_Users WHERE UserID = ?");
        $stmt2->bind_param("i", $user_id);
        $stmt2->execute();
        $stmt2->close();

        // Commit the transaction
        $conn->commit();

        // Destroy the session
        session_destroy();

        // Redirect to login page with success message
        header("Location: ../view/login.php?status=deleted");
        exit;
    } catch (Exception $e) {
        // Rollback the transaction
        $conn->rollback();

        // Redirect with error message
        header("Location: ../view/users.php?status=delete_error");
        exit;
    }
} else {
    // If no user ID is found in the session
    header("Location: ../view/login.php?status=unauthorized");
    exit;
}

$conn->close();
?>