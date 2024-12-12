<?php
session_start();
require_once '../db/config.php';
require_once '../actions/get_restaurants.php';

// Check if user is logged in
if (!isset($_SESSION['UserID'])) {
    // Redirect to login page if not logged in
    header("Location: ../view/login.php");
    exit();
}

// Get current user's first name from session or database
$firstName = $_SESSION['FirstName'] ?? '';

// Handle search functionality
$searchResults = [];
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search'])) {
    $searchTerm = trim($_GET['search']);
    
    if (!empty($searchTerm)) {
        // Prepare SQL to search restaurants
        $stmt = $conn->prepare("
            SELECT r.*,
            COALESCE(AVG(rt.Rating), 0) AS AverageRating
            FROM DWB_Restaurants r
            LEFT JOIN DWB_Ratings rt ON r.RestaurantID = rt.RestaurantID
            WHERE r.ResName LIKE ? OR
            r.ResAddress LIKE ? OR
            r.AccessibilityFeatures LIKE ?
            GROUP BY
                r.RestaurantID,
                r.UserID,
                r.ResName,
                r.ResAddress,
                r.PhoneNumber,
                r.AccessibilityFeatures,
                r.RestaurantImage,
                r.CreatedAt
        ");
        
        $searchParam = "%$searchTerm%";
        $stmt->bind_param("sss", $searchParam, $searchParam, $searchParam);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $searchResults[] = $row;
        }
        $stmt->close();
    }
}

$exploreRestaurants = getExploreRestaurants();

?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/wheelchair_style.css">
    <title>Restaurant user page</title>
</head>
<body>
    <!-- Header Section -->
    <header class="top">
        <div class="top-section">
            <h1>Welcome <?php echo htmlspecialchars($firstName); ?></h1>
            <p>You are a search away from inclusive dining!</p>
            
            <!-- Search Form -->
            <form action="" method="GET" class="search-box">
                <input type="text" name="search" placeholder="Search for Restaurants"
                    value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                <button type="submit">Search</button>
            </form>
            
            <a href="../view/restaurant.php">Go to Dashboard</a>
        </div>
    </header>

    <!-- Search Results Section -->
    <?php if (!empty($searchResults)): ?>
        <section class="search-results">
            <h2>Search Results</h2>
            <div class="restaurants-section" id="search-restaurants">
                <?php foreach ($searchResults as $restaurant): ?>
                <div class="restaurants-card" data-restaurant-id="<?php echo $restaurant['RestaurantID']; ?>">
                    <img src="<?php echo htmlspecialchars($restaurant['RestaurantImage']); ?>" alt="Image of <?php echo htmlspecialchars($restaurant['ResName']); ?>">
                    <h2><?php echo htmlspecialchars($restaurant['ResName']); ?></h2>
                    <p><?php echo htmlspecialchars($restaurant['AccessibilityFeatures']); ?></p>
                    <div class="rating-container">
                        <div class="rating">
                            <?php
                            $rating = isset($restaurant['AverageRating']) && $restaurant['AverageRating'] !== null
                                ? round($restaurant['AverageRating'])
                                : 0; // Default to 0 if no rating
                        
                            for ($i = 1; $i <= 5; $i++) {
                                echo $i <= $rating ? '&#9733;' : '&#9734;';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="button-container">
                        <a href="#" class="button view-btn">View</a>
                        
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php elseif (isset($_GET['search']) && empty($searchResults)): ?>
        <section class="no-results">
            <p>No restaurants found matching your search.</p>
        </section>
    <?php endif; ?>

    

    <!--The code for the restaurants section-->
    <section class="restaurants">
        <!-- Explore Restaurants (now dynamically populated) -->
        <h1>Explore Restaurants</h1>
        <div class="restaurants-section" id="explore-restaurants">
            <?php foreach ($exploreRestaurants as $restaurant): ?>
            <div class="restaurants-card" data-restaurant-id="<?php echo $restaurant['RestaurantID']; ?>">
                <img src="<?php echo htmlspecialchars($restaurant['RestaurantImage']); ?>" alt="Image of <?php echo htmlspecialchars($restaurant['ResName']); ?>">
                <h2><?php echo htmlspecialchars($restaurant['ResName']); ?></h2>
                <p><?php echo htmlspecialchars($restaurant['AccessibilityFeatures']); ?></p>
                <div class="rating-container">
                    <div class="rating">
                        <?php
                        $rating = isset($restaurant['AverageRating']) && $restaurant['AverageRating'] !== null
                            ? round($restaurant['AverageRating'])
                            : 0; // Default to 0 if no rating
                
                        for ($i = 1; $i <= 5; $i++) {
                            echo $i <= $rating ? '&#9733;' : '&#9734;';
                        }
                        ?>
                    </div>
                </div>
                <div class="button-container">
                    <a href="#" class="button view-btn">View</a>
                    
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Modal for Restaurant Details -->
    <div id="restaurant-modal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <img id="modal-image" src="" alt="Restaurant Image">
            <h2 id="modal-name"></h2>
            <p id="modal-address"></p>
            <p id="modal-phone"></p>
            <p id="modal-accessibility"></p>
            
            <div id="modal-rating"></div>
        </div>
        
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
        // View Restaurant Details
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const restaurantCard = this.closest('.restaurants-card');
                const restaurantId = restaurantCard.getAttribute('data-restaurant-id');
                
                fetch(`../actions/get_restaurant_details.php?id=${restaurantId}`)
                    .then(response => response.json())
                    .then(restaurant => {
                        document.getElementById('modal-image').src = restaurant.RestaurantImage;
                        document.getElementById('modal-name').textContent = restaurant.ResName;
                        document.getElementById('modal-address').textContent = `Address: ${restaurant.ResAddress}`;
                        document.getElementById('modal-phone').textContent = `Phone: ${restaurant.PhoneNumber}`;
                        document.getElementById('modal-accessibility').textContent = `Accessibility: ${restaurant.AccessibilityFeatures}`;
                        
                        const modal = document.getElementById('restaurant-modal');
                        modal.style.display = 'flex';
                    });
            });
        });

        

        

        // Close Modals
        document.querySelectorAll('.close, .close-btn').forEach(closeBtn => {
            closeBtn.addEventListener('click', closeModals);
        });

        function closeModals() {
            document.getElementById('restaurant-modal').style.display = 'none';
            document.getElementById('rateModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            const restaurantModal = document.getElementById('restaurant-modal');
            const rateModal = document.getElementById('rateModal');
            
            if (event.target === restaurantModal) {
                restaurantModal.style.display = 'none';
            }
            
            if (event.target === rateModal) {
                rateModal.style.display = 'none';
            }
        });
    });
    </script>
</body>
</html>