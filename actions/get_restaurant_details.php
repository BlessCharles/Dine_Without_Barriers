<?php
require_once '../db/config.php';
require_once '../actions/get_restaurants.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// The code to check if restaurant ID is provided
if (isset($_GET['id'])) {
    $restaurantId = intval($_GET['id']);
    $restaurant = getRestaurantDetails($restaurantId);
    
    if ($restaurant) {
        //The code to ensure all the expected fields exist
        $safeRestaurant = [
            'RestaurantImage' => $restaurant['RestaurantImage'] ?? '../assets/images/default-restaurant.jpg',
            'ResName' => $restaurant['ResName'] ?? 'Restaurant Name Unavailable',
            'ResAddress' => $restaurant['ResAddress'] ?? 'Address Not Available',
            'PhoneNumber' => $restaurant['PhoneNumber'] ?? 'Phone Not Available',
            'AccessibilityFeatures' => $restaurant['AccessibilityFeatures'] ?? 'No accessibility information'
        ];
        
        //The code to return restaurant details as JSON
        header('Content-Type: application/json');
        echo json_encode($safeRestaurant);
    } else {
        
        http_response_code(404);
        echo json_encode(['error' => 'Restaurant not found']);
    }
} else {
    
    http_response_code(400);
    echo json_encode(['error' => 'No restaurant ID provided']);
}
?>