<?php
session_start();
require_once '../db/config.php';

//The code to ensure user is logged in
if (!isset($_SESSION['UserID'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$userId = $_SESSION['UserID'];

//The code to fetch user ratings with restaurant names
$sql = "SELECT r.ResName, rt.Rating
        FROM DWB_Ratings rt
        JOIN DWB_Restaurants r ON rt.RestaurantID = r.RestaurantID
        WHERE rt.UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$ratings = [];
while ($row = $result->fetch_assoc()) {
    $ratings[] = $row;
}

echo json_encode([
    'success' => true,
    'ratings' => $ratings
]);

$stmt->close();
$conn->close();
?>