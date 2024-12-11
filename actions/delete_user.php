<?php
// The code to connect to the database
include '../db/config.php';

// The code to check if the delete action is triggered
if (isset($_GET['UserID'])) {
    $user_id = $_GET['UserID'];

    //The code for the SQL query to delete the user
    $sql = "DELETE FROM DWB_Users WHERE UserID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    // The code to redirect to the users page when successful
    header("Location: ../view/users.php?status=success");
    exit;
}

//The code to redirect to users page with error if delete action was not successful
header("Location: ../view/users.php?status=error");
exit;
?>