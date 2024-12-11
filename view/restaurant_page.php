<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/wheelchair_style.css">
    <title>Restaurant user page</title>
</head>
<body>
    <!--The code for the top part of the page where there is a few words and a search box to filter the recipes by a keyword-->
    <header class="top">
        <div class="top-section">
            <h1>Welcome Bless</h1>
            <p>You are a search away from inclusive dining!</p>
            <form action="#" class="search-box">
                <input type="text" id="search-input" placeholder="Search for Restaurants">
                <button type="submit">Search</button>
            </form>
            <a href="../view/restaurant.php">Go to Dashboard</a>
        </div>
    </header>

    <!--The code for the restaurants section-->
    <section class="restaurants">
        <h1>Explore restaurants</h1>
        <div id="restaurants-section" class="restaurants-section">
            <!-- Restaurants will be dynamically populated here -->
        </div>
    </section>

    <!-- Modal for Restaurant Details -->
    <div id="restaurant-modal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <img id="modal-image" src="" alt="Restaurant Image">
            <h2 id="modal-name"></h2>
            <p id="modal-location"></p>
            <div id="modal-rating"></div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const restaurantsSection = document.getElementById('restaurants-section');
            const searchInput = document.getElementById('search-input');

            // Fetch restaurants from the database
            function fetchRestaurants() {
                fetch('../actions/get_restaurants.php')
                    .then(response => response.json())
                    .then(restaurants => {
                        // Clear existing restaurants
                        restaurantsSection.innerHTML = '';

                        // Populate restaurants
                        restaurants.forEach(restaurant => {
                            const restaurantCard = createRestaurantCard(restaurant);
                            restaurantsSection.appendChild(restaurantCard);
                        });

                        // Re-initialize modal after adding new cards
                        initializeModal();
                    })
                    .catch(error => {
                        console.error('Error fetching restaurants:', error);
                        restaurantsSection.innerHTML = '<p>Error loading restaurants. Please try again later.</p>';
                    });
            }

            // Create restaurant card dynamically
            function createRestaurantCard(restaurant) {
                const card = document.createElement('div');
                card.className = 'restaurants-card';
                
                // Convert average rating to stars
                const starRating = generateStarRating(parseFloat(restaurant.AverageRating));

                card.innerHTML = `
                    <img src="${restaurant.RestaurantImage || '../assets/images/default-restaurant.jpg'}" alt="Image of ${restaurant.ResName}">
                    <h2>${restaurant.ResName}</h2>
                    <p>${restaurant.AccessibilityFeatures || 'No specific accessibility info'}</p>
                    <div class="rating-container">
                        <div class="rating">
                            ${starRating}
                        </div>
                    </div>
                    <div class="button-container">
                        <a href="#" class="button" data-restaurant-id="${restaurant.RestaurantID}">View</a>
                    </div>
                `;

                return card;
            }

            // Generate star rating HTML
            function generateStarRating(rating) {
                const fullStars = Math.floor(rating);
                const halfStar = rating % 1 >= 0.5 ? 1 : 0;
                const emptyStars = 5 - fullStars - halfStar;

                let starHtml = '';
                
                // Full stars
                for (let i = 0; i < fullStars; i++) {
                    starHtml += '<span>&#9733;</span>';
                }
                
                // Half star
                if (halfStar) {
                    starHtml += '<span>&#9734;&#9733;</span>';
                }
                
                // Empty stars
                for (let i = 0; i < emptyStars; i++) {
                    starHtml += '<span>&#9734;</span>';
                }

                return starHtml;
            }

            function initializeModal() {
                const modal = document.getElementById("restaurant-modal");
                const modalImage = document.getElementById("modal-image");
                const modalName = document.getElementById("modal-name");
                const modalLocation = document.getElementById("modal-location");
                const modalRating = document.getElementById("modal-rating");
                const closeModal = document.querySelector(".close-btn");

                // Add event listeners to "View" buttons
                document.querySelectorAll(".button").forEach((button) => {
                    button.addEventListener("click", (event) => {
                        event.preventDefault();
                        const restaurantId = button.getAttribute('data-restaurant-id');

                        // Fetch restaurant details
                        fetch(`../view/get_restaurant_details.php?id=${restaurantId}`)
                            .then(response => response.json())
                            .then(data => {
                                modalImage.src = data.RestaurantImage;
                                modalName.textContent = data.ResName;
                                modalLocation.textContent = `Location: ${data.ResAddress}`;
                                modalRating.innerHTML = generateStarRating(3); // You might want to pass actual rating here

                                modal.style.display = "flex";
                            })
                            .catch(error => {
                                console.error('Error fetching restaurant details:', error);
                            });
                    });
                });

                // Close modal
                closeModal.addEventListener("click", () => {
                    modal.style.display = "none";
                });

                // Close modal when clicking outside the content
                window.addEventListener("click", (event) => {
                    const modal = document.getElementById("restaurant-modal");
                    if (event.target === modal) {
                        modal.style.display = "none";
                    }
                });
            }

            // Search functionality
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const cards = document.querySelectorAll('.restaurants-card');
                
                cards.forEach(card => {
                    const restaurantName = card.querySelector('h2').textContent.toLowerCase();
                    const accessibilityInfo = card.querySelector('p').textContent.toLowerCase();
                    
                    if (restaurantName.includes(searchTerm) || accessibilityInfo.includes(searchTerm)) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });

            // Initial fetch of restaurants
            fetchRestaurants();
        });
    </script>
</body>
</html>