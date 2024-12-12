<?php
require_once '../db/config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

function getExploreRestaurants() {
    global $conn;
    
    // Get restaurants sorted by average rating
    $sql = "SELECT r.RestaurantID, r.ResName, r.AccessibilityFeatures, r.RestaurantImage,
                COALESCE(AVG(rt.Rating), 0) as AverageRating
            FROM DWB_Restaurants r
            LEFT JOIN DWB_Ratings rt ON r.RestaurantID = rt.RestaurantID
            GROUP BY r.RestaurantID
            ORDER BY AverageRating DESC";
    
    $result = $conn->query($sql);
    
    $restaurants = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $restaurants[] = $row;
        }
    }
    
    return $restaurants;
}


// Fetch restaurants and return as JSON
$restaurants = getExploreRestaurants();
header('Content-Type: application/json');
echo json_encode($restaurants);


function getRestaurantDetails($restaurantId) {
    global $conn;
    
    $sql = "SELECT RestaurantID, ResName, ResAddress, PhoneNumber, AccessibilityFeatures, RestaurantImage 
            FROM DWB_Restaurants
            WHERE RestaurantID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $restaurantId);
    $stmt->execute();
    
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function saveRestaurantRating($restaurantId, $userId, $rating) {
    global $conn;
    
    $sql = "INSERT INTO DWB_Ratings (RestaurantID, UserID, Rating)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE Rating = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $restaurantId, $userId, $rating, $rating);
    
    return $stmt->execute();
}
?>