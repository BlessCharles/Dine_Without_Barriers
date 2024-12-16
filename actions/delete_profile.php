<?php
// The code to connect to the database
include '../db/config.php';
session_start();

// The code to check if the delete action is triggered
if (isset($_SESSION['UserID'])) {
    $user_id = $_SESSION['UserID'];

    // The code to start a transaction
    $conn->begin_transaction();

    try {
        //the code to delete related records in other tables if necessary
        
        $stmt1 = $conn->prepare("DELETE FROM DWB_Ratings WHERE UserID = ?");
        $stmt1->bind_param("i", $user_id);
        $stmt1->execute();
        $stmt1->close();

        // the code to then delete the user
        $stmt2 = $conn->prepare("DELETE FROM DWB_Users WHERE UserID = ?");
        $stmt2->bind_param("i", $user_id);
        $stmt2->execute();
        $stmt2->close();

        // the code to commit the transaction
        $conn->commit();

        // the code to destroy the session
        session_destroy();

        // The code to redirect to login page with success message
        header("Location: ../view/login.php?status=deleted");
        exit;
    } catch (Exception $e) {
        // The code to rollback the transaction
        $conn->rollback();

        // The code to redirect with error message
        header("Location: ../view/users.php?status=delete_error");
        exit;
    }
} else {
    // The code for redirection if no user ID is found in the session
    header("Location: ../view/login.php?status=unauthorized");
    exit;
}

$conn->close();
?>