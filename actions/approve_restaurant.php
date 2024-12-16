<?php
include '../db/config.php';
session_start();

// The code to check if user is admin
if (!isset($_SESSION['UserID'])) {
    header('Location: ../view/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pendingId'])) {
    $pendingId = $_POST['pendingId'];
    
    if ($_POST['action'] == 'approve') {
        // The code to fetch the pending restaurant details from the database
        $stmt = $conn->prepare("SELECT * FROM DWB_Restaurant_Pending WHERE PendingID = ?");
        $stmt->bind_param("i", $pendingId);
        $stmt->execute();
        $result = $stmt->get_result();
        $restaurant = $result->fetch_assoc();
        
        if ($restaurant) {
            // The code to insert the restaurant information into main restaurants table
            $insertStmt = $conn->prepare("INSERT INTO DWB_Restaurants
                (UserID, ResName, ResAddress, PhoneNumber, AccessibilityFeatures, RestaurantImage)
                VALUES (?, ?, ?, ?, ?, ?)");
            $insertStmt->bind_param("isssss",
                $restaurant['UserID'],
                $restaurant['ResName'],
                $restaurant['ResAddress'],
                $restaurant['PhoneNumber'],
                $restaurant['AccessibilityFeatures'],
                $restaurant['RestaurantImage']
            );
            
            if ($insertStmt->execute()) {
                // The code to remove the information from the restaurant pending table
                $deleteStmt = $conn->prepare("DELETE FROM DWB_Restaurant_Pending WHERE PendingID = ?");
                $deleteStmt->bind_param("i", $pendingId);
                $deleteStmt->execute();
                
                $_SESSION['message'] = "Restaurant approved successfully";
            } else {
                $_SESSION['error'] = "Failed to approve restaurant";
            }
        }
    } elseif ($_POST['action'] == 'disapprove') {
        //The code to delete the information from the pending table if disapproved
        $deleteStmt = $conn->prepare("DELETE FROM DWB_Restaurant_Pending WHERE PendingID = ?");
        $deleteStmt->bind_param("i", $pendingId);
        
        if ($deleteStmt->execute()) {
            $_SESSION['message'] = "Restaurant request disapproved";
        } else {
            $_SESSION['error'] = "Failed to disapprove restaurant";
        }
    }
}

header('Location: ../view/restaurants_manage.php');
exit;
?>