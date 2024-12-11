<?php
require_once '../db/config.php';
require_once '../actions/get_restaurants.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if restaurant ID is provided
if (isset($_GET['id'])) {  // Note: changed from 'RestaurantID' to 'id' to match the JavaScript fetch
    $restaurantId = intval($_GET['id']);
    $restaurant = getRestaurantDetails($restaurantId);
    
    if ($restaurant) {
        // Ensure all expected keys exist, even if they're null
        $safeRestaurant = [
            'RestaurantImage' => $restaurant['RestaurantImage'] ?? '../assets/images/default-restaurant.jpg',
            'ResName' => $restaurant['ResName'] ?? 'Restaurant Name Unavailable',
            'ResAddress' => $restaurant['ResAddress'] ?? 'Address Not Available',
            'PhoneNumber' => $restaurant['PhoneNumber'] ?? 'Phone Not Available',
            'AccessibilityFeatures' => $restaurant['AccessibilityFeatures'] ?? 'No accessibility information'
        ];
        
        // Return restaurant details as JSON
        header('Content-Type: application/json');
        echo json_encode($safeRestaurant);
    } else {
        // Restaurant not found
        http_response_code(404);
        echo json_encode(['error' => 'Restaurant not found']);
    }
} else {
    // No ID provided
    http_response_code(400);
    echo json_encode(['error' => 'No restaurant ID provided']);
}
?>