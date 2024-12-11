<?php
//The code to connect to the database
include '../db/config.php';

//The code to enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

//The code to start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['UserID'])) {
    header('Location: ../view/login.php');
    exit;
}

// Get current restaurant's ID (assuming it's stored in session)
$restaurantId = $_SESSION['RestaurantID'];

// Fetch restaurant details
$restaurantQuery = "SELECT * FROM DWB_Restaurants WHERE RestaurantID = ?";
$restaurantStmt = $conn->prepare($restaurantQuery);
$restaurantStmt->bind_param("i", $restaurantId);
$restaurantStmt->execute();
$restaurantResult = $restaurantStmt->get_result();
$restaurantDetails = $restaurantResult->fetch_assoc();

// Fetch ratings for this restaurant
$ratingsQuery = "SELECT u.FirstName, u.LastName, r.Rating
                FROM DWB_Ratings r
                JOIN DWB_Users u ON r.UserID = u.UserID
                WHERE r.RestaurantID = ?";
$ratingsStmt = $conn->prepare($ratingsQuery);
$ratingsStmt->bind_param("i", $restaurantId);
$ratingsStmt->execute();
$ratingsResult = $ratingsStmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/restaurant_style.css">
    <title>Restaurant Dashboard</title>
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: #fefefe;
            padding: 20px;
            border-radius: 10px;
            width: 80%;
            max-width: 500px;
            text-align: center;
        }
        .modal input, .modal button {
            margin: 10px 0;
            padding: 10px;
            width: 100%;
        }
    </style>
</head>
<body>
    
    <!-- [Previous top navigation and cards section remains the same] -->
    <!-- The code for the top navigation bar (where the search box and the notification icon is located) -->
    <div class="container">
        <header class="top-section">
            <div class="navigation">
                <div class="branding">
                    <img src="../assets/images/DIne_without_Barrirs-removebg-preview.png" alt="Dine Without Barriers Logo" class="logo">
                    <h1 class="name">Dine Without Barriers</h1>
                </div>
                <div class="notification">
                    <img src="../assets/images/4850517_alert_bell_notification_ring_snooze_icon.png" alt="Notification Icon">
                </div>
            </div>
        </header>

        <!-- Content section -->
        <div class="content">
            <section class="cards">
                <!-- Restaurant Profile Card -->
                <div class="card" id="restaurantProfileCard">
                    <div class="box">
                        <h3>Restaurant Profile</h3>
                    </div>
                    <div class="icon">
                        <img src="../assets/images/9336574_chef_restaurant_man_profile_icon.png" alt="Restaurant Icon">
                    </div>
                </div>

                <!-- Restaurant Management Card -->
                <div class="card" id="restaurantManagementCard">
                    <div class="box">
                        <h3>Add Restaurant Details</h3>
                    </div>
                    <div class="icon">
                        <img src="../assets/images/352514_local_restaurant_icon.png" alt="Management Icon">
                    </div>
                </div>

                <!-- View Ratings Card -->
                <div class="card" id="viewRatingsCard">
                    <div class="box">
                        <h3>View Ratings</h3>
                    </div>
                    <div class="icon">
                        <img src="../assets/images/1564507_checked_favorite_star_favourite_rating_icon.png" alt="Ratings Icon">
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- Restaurant Profile Modal -->
    <div id="restaurantProfileModal" class="modal">
        <div class="modal-content">
            <h2>Restaurant Profile</h2>
            <p><strong>Restaurant Name:</strong> <?php echo htmlspecialchars($restaurantDetails['ResName']); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($restaurantDetails['ResAddress']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($restaurantDetails['PhoneNumber']); ?></p>
            <p><strong>Accessibility Features:</strong> <?php echo htmlspecialchars($restaurantDetails['AccessibilityFeatures']); ?></p>
            
            <button onclick="openEditProfileModal()">Edit Profile</button>
            <button onclick="deleteRestaurantAccount()">Delete Account</button>
            <button onclick="logout()">Logout</button>
            <button onclick="closeModal('restaurantProfileModal')">Close</button>
        </div>
    </div>

    <!-- Edit Restaurant Profile Modal -->
    <div id="editRestaurantProfileModal" class="modal">
        <div class="modal-content">
            <h2>Edit Restaurant Profile</h2>
            <form id="editRestaurantForm" action="../actions/update_restaurant.php" method="POST" enctype="multipart/form-data">
                <label for="restaurantName">Restaurant Name:</label>
                <input type="text" id="restaurantName" name="ResName"
                    value="<?php echo htmlspecialchars($restaurantDetails['ResName']); ?>" required>
                
                <label for="restaurantAddress">Address:</label>
                <input type="text" id="restaurantAddress" name="ResAddress"
                    value="<?php echo htmlspecialchars($restaurantDetails['ResAddress']); ?>" required>
                
                <label for="restaurantPhone">Phone:</label>
                <input type="text" id="restaurantPhone" name="PhoneNumber"
                    value="<?php echo htmlspecialchars($restaurantDetails['PhoneNumber']); ?>" required>

                <label for="restaurantFeatures">Accessibility Features:</label>
                <input type="text" id="restaurantFeatures" name="AccessibilityFeatures"
                    value="<?php echo htmlspecialchars($restaurantDetails['AccessibilityFeatures']); ?>" required>
                
                <label for="restaurantImage">Restaurant Image:</label>
                <input type="file" id="restaurantImage" name="restaurantImage" accept="image/*">
                
                <button type="submit">Update Profile</button>
                <button type="button" onclick="closeModal('editRestaurantProfileModal')">Cancel</button>
            </form>
        </div>
    </div>

    <!-- Add/Manage Restaurant Details Modal -->
    <div id="restaurantManagementModal" class="modal">
        <div class="modal-content">
            <h2>Add/Manage Restaurant Details</h2>
            <form id="restaurantForm" action="../actions/add_restaurant.php" method="POST" enctype="multipart/form-data">
                <label for="restaurantName">Restaurant Name:</label>
                <input type="text" id="restaurantName" name="ResName" placeholder="Enter Restaurant Name" required>
                
                <label for="restaurantAddress">Address:</label>
                <input type="text" id="restaurantAddress" name="ResAddress" placeholder="Enter Address" required>
                
                <label for="restaurantPhone">Phone:</label>
                <input type="text" id="restaurantPhone" name="PhoneNumber" placeholder="Enter Phone Number" required>

                <label for="restaurantFeatures">Accessibility Features:</label>
                <input type="text" id="restaurantFeatures" name="AccessibilityFeatures" placeholder="Enter Accessibility Features" required>
                
                <label for="restaurantImage">Restaurant Image:</label>
                <input type="file" id="restaurantImage" name="restaurantImage" accept="image/*" required>
                
                <button type="submit">Add Restaurant Details</button>
                <button type="button" onclick="closeModal('restaurantManagementModal')">Close</button>
            </form>
        </div>
    </div>

    <!-- View Ratings Modal -->
    <div id="viewRatingsModal" class="modal">
        <div class="modal-content">
            <h2>Restaurant Ratings</h2>
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Rating</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($rating = $ratingsResult->fetch_assoc()) {
                        // Convert numeric rating to star representation
                        $starRating = str_repeat('⭐', $rating['Rating']);
                        echo "<tr>
                                <td>" . htmlspecialchars($rating['FirstName'] . ' ' . $rating['LastName']) . "</td>
                                <td>$starRating</td>
                            </tr>";
                    }
                    ?>
                </tbody>
            </table>
            <button onclick="closeModal('viewRatingsModal')">Close</button>
        </div>
    </div>

    <script>
        function openEditProfileModal() {
            closeModal('restaurantProfileModal');
            openModal('editRestaurantProfileModal');
        }

        function deleteRestaurantAccount() {
            if (confirm('Are you sure you want to delete your restaurant account? This action cannot be undone.')) {
                window.location.href = '../actions/delete_restaurant.php';
            }
        }

        function logout() {
            window.location.href = '../actions/logout.php';
        }

        // Existing modal functions from previous script
        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'flex';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Event listeners for cards
        document.getElementById('restaurantProfileCard').addEventListener('click', function() {
            openModal('restaurantProfileModal');
        });

        document.getElementById('restaurantManagementCard').addEventListener('click', function() {
            openModal('restaurantManagementModal');
        });

        document.getElementById('viewRatingsCard').addEventListener('click', function() {
            openModal('viewRatingsModal');
        });
    </script>
</body>
</html>

<?php
// Close database connections
$restaurantStmt->close();
$ratingsStmt->close();
$conn->close();
?>













