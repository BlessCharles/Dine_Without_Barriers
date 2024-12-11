
<!DOCTYPE html>
<html>
<head>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/wheelchair_style.css">
    <title>Wheelchair user page</title>
</head>
<body>
    <!--The code for the top part of the page where there is a few words and a search box to filter the recipes by a keyword-->
    <header class="top">
        <div class="top-section">
            <h1>Welcome Bless</h1>
            <p> You are a search away from inclusive dining!</p>
            <form action="#" class="search-box" >
                <input type="text" placeholder="Search for Restaurants">
                <button type="submit">Search</button>
            </form>
            <a href="../view/restaurant.php">Go to Dashboard</a>
        </div>

    </header>

    <!--The code for the featured recipes, with their picture, name, short description and rating-->
    <section class="restaurants">
        <h1>Featured Restaurants</h1>
        

        <div class="restaurants-section">
            <div class="restaurants-card">
                <img src ="../assets/images/elevate-RBuF5GfN8ts-unsplash.jpg" alt="Image of Bossman restaurant">
                <h2>Bossman restaurant</h2>
                <p>Dining with a touch of family</p>
                <!--The code for how the stars are filled or colored-->
                <div class="rating-container">
                    <div class="rating">
                        <span>&#9733;</span><!--The code for a filled or colored star-->
                        <span>&#9733;</span>
                        <span>&#9733;</span>
                        <span>&#9733;</span>
                        <span>&#9734;</span><!--The code for an empty or unfilled star-->
                    </div>
                </div>
                <div class="button-container">
                    <a href="#" class="button">View</a>
                    
                </div>
            </div>
        
            

            <div class="restaurants-card">
                <img src ="../assets/images/elevate-vofmJUVScDE-unsplash.jpg" alt="Image of Penaky Diner">
                <h2>Penaky Diner</h2>
                <p>Dine with fresh blueberry wine</p>
                <div class="rating-container">
                    <div class="rating">
                        <span>&#9733;</span>
                        <span>&#9733;</span>
                        <span>&#9733;</span>
                        <span>&#9733;</span>
                        <span>&#9734;</span>
                    </div>
                </div>
                <div class="button-container">
                    <a href="#" class="button">View</a>
                    
                </div>
            </div>

            <div class="restaurants-card">
                <img src ="../assets/images/pexels-elevate-3009803.jpg" alt="Image of Friends of Friends">
                <h2>Friends of Friends</h2>
                <p>Best Pasta place with friends</p>
                <div class="rating-container">
                    <div class="rating">
                        <span>&#9733;</span>
                        <span>&#9733;</span>
                        <span>&#9733;</span>
                        <span>&#9734;</span>
                        <span>&#9734;</span>
                    </div>
                </div>
                <div class="button-container">
                    <a href="#" class="button">View</a>
                    
                </div>
            </div>

            <div class="restaurants-card">
                <img src ="../assets/images/shivani-g-bROdjExthjA-unsplash.jpg" alt="Image of Poky Pee">
                <h2>Poky Pee</h2>
                <p>A relaxing morning with the best breakfast</p>
                <div class="rating-container">
                    <div class="rating">
                        <span>&#9733;</span>
                        <span>&#9733;</span>
                        <span>&#9733;</span>
                        <span>&#9733;</span>
                        <span>&#9734;</span>
                    </div>
                </div>
                <div class="button-container">
                    <a href="#" class="button">View</a>
                    
                </div>
            </div>

        
        </div>

        <!--The code for the breakfast options on the recipe page-->
        <h1>Explore restaurants</h1>
        <div class="restaurants-section">
            <div class="restaurants-card">
                <img src ="../assets/images/pexels-abdellah-ziki-1621531168-29473845.jpg" alt="Image of Asanka">
                <h2>Asanka</h2>
                <p>A taste of home</p>
                <div class="rating-container">
                    <div class="rating">
                        <span>&#9733;</span>
                        <span>&#9733;</span>
                        <span>&#9733;</span>
                        <span>&#9734;</span>
                        <span>&#9734;</span>
                    </div>
                </div>
                <div class="button-container">
                    <a href="#" class="button">View</a>
                    
                </div>
            </div>

            <div class="restaurants-card">
                <img src ="../assets/images/pexels-bertellifotografia-16674093.jpg" alt="Image of Hallmark">
                <h2>Hallmark</h2>
                <p>The first listening restaurant</p>
                <div class="rating-container">
                    <div class="rating">
                        <span>&#9733;</span>
                        <span>&#9733;</span>
                        <span>&#9734;</span>
                        <span>&#9734;</span>
                        <span>&#9734;</span>
                    </div>
                </div>
                <div class="button-container">
                    <a href="#" class="button">View</a>
                    
                </div>
            </div>

            <div class="restaurants-card">
                <img src ="../assets/images/pexels-pixabay-262978.jpg" alt="Image of munchies">
                <h2>Munchies</h2>
                <p>Always available</p>
                <div class="rating-container">
                    <div class="rating">
                        <span>&#9733;</span>
                        <span>&#9733;</span>
                        <span>&#9733;</span>
                        <span>&#9733;</span>
                        <span>&#9734;</span>
                    </div>
                </div>
                <div class="button-container">
                    <a href="#" class="button">View</a>
                    
                </div>
            </div>


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
        function initializeModal() {
            const modal = document.getElementById("restaurant-modal");
            const modalImage = document.getElementById("modal-image");
            const modalName = document.getElementById("modal-name");
            const modalLocation = document.getElementById("modal-location");
            const modalRating = document.getElementById("modal-rating");
            const closeModal = document.querySelector(".close-btn");

            // Data for restaurants
            const restaurantData = {
                bossmanrestaurant: {
                    image: "../assets/images/elevate-RBuF5GfN8ts-unsplash.jpg",
                    name: "Bossman Restaurant",
                    location: "Accra, Ghana",
                    rating: "⭐⭐⭐⭐☆",
                },
                
                asanka: {
                    image: "../assets/images/pexels-abdellah-ziki-1621531168-29473845.jpg",
                    name: "Asanka",
                    location: "Spintex, Ghana",
                    rating: "⭐⭐⭐☆☆",
                },
            };

            // Add event listeners to "View" buttons
            document.querySelectorAll(".button").forEach((button) => {
                button.addEventListener("click", (event) => {
                    if (button.textContent === "View") {
                        const restaurantId = button.closest(".restaurants-card").querySelector("h2").textContent.toLowerCase().replace(/\s/g, '');
                        const data = restaurantData[restaurantId];

                        if (data) {
                            modalImage.src = data.image;
                            modalName.textContent = data.name;
                            modalLocation.textContent = `Location: ${data.location}`;
                            modalRating.textContent = data.rating;
                            modal.style.display = "flex";
                        }
                    }
                });
            });

            // Close modal
            closeModal.addEventListener("click", () => {
                modal.style.display = "none";
            });

            // Close modal when clicking outside the content
            window.addEventListener("click", (event) => {
                if (event.target === modal) {
                    modal.style.display = "none";
                }
            });
        }

        // Initialize modal functionality
        initializeModal();

        

    </script>
</body>
</html>