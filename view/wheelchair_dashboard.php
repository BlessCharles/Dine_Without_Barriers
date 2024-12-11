<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/wheeldash_style.css">
    <title>Wheelchair user dashboard</title>
</head>
<body>
    <!-- Previous header and navigation code remains the same -->
    <!-- The code for the top navigation bar (where the search box and the navigation icon is located)-->
    <div class="container">
        <header class="top-section">
            <div class="navigation">
                <div class="branding">
                    <img src="../assets/images/DIne_without_Barrirs-removebg-preview.png" alt="Dine Without Barriers Logo" class="logo">
                    <h1 class="name">Dine Without Barriers</h1>
                </div>
                <!-- The code for the notification icon aligned to the right -->
                <div class="notification">
                    <img src="../assets/images/4850517_alert_bell_notification_ring_snooze_icon.png" alt="Notification Icon">
                </div>
            </div>
        </header>

        <!-- The code for the 4 cards that show beneath the header -->
        <div class="content">
            <section class="cards">
                <!-- User Profile Card -->
                <div class="card" id="userProfileCard">
                    <div class="box">
                        <h3>User Profile</h3>
                    </div>
                    <div class="icon">
                        <img src="../assets/images/1564534_customer_man_user_account_profile_icon.png" alt="Users icon">
                    </div>
                </div>

                <!-- My Ratings Card -->
                <div class="card" id="ratingsCard">
                    <div class="box">
                        <h3>My Ratings</h3>
                    </div>
                    <div class="icon">
                        <img src="../assets/images/1564507_checked_favorite_star_favourite_rating_icon.png" alt="ratings icon">
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- Modals remain mostly the same, but we'll modify their content dynamically -->
    <!-- User Profile Pop-Up Modal -->
    <div id="userProfileModal" class="modal">
        <div class="modal-content">
            <h2>User Profile</h2>
            <p><strong>Full Name:</strong> <span id="profileFullName"></span></p>
            <p><strong>Email:</strong> <span id="profileEmail"></span></p>
            <div class="button-container">
                <button id="editProfileBtn">Edit</button>
                <button id="deleteAccountBtn">Delete Account</button>
                <button id="logoutBtn">Logout</button>
                <button id="userProfileModalClose">Close</button>
            </div>
        </div>
    </div>

    <!-- My Ratings Pop-Up Modal -->
    <div id="ratingsModal" class="modal">
        <div class="modal-content">
            <h2>My Ratings</h2>
            <table id="ratingsTable">
                <thead>
                    <tr>
                        <th>Restaurant</th>
                        <th>Rating</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="ratingsTableBody">
                    <!-- Ratings will be dynamically populated here -->
                </tbody>
            </table>
            <button id="ratingsModalClose">Close</button>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Open User Profile modal and fetch user data
        document.getElementById('userProfileCard').addEventListener('click', function() {
            fetch('../actions/get_user_profile.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('profileFullName').textContent = data.fullName;
                        document.getElementById('profileEmail').textContent = data.email;
                        document.getElementById('userProfileModal').style.display = 'flex';
                    } else {
                        alert('Failed to fetch user profile');
                    }
                });
        });

        // Open Ratings modal and fetch ratings
        document.getElementById('ratingsCard').addEventListener('click', function() {
            fetch('../actions/get_user_ratings.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const tableBody = document.getElementById('ratingsTableBody');
                        tableBody.innerHTML = ''; // Clear existing rows

                        data.ratings.forEach(rating => {
                            const row = document.createElement('tr');
                            
                            // Restaurant Name
                            const nameCell = document.createElement('td');
                            nameCell.textContent = rating.ResName;
                            row.appendChild(nameCell);

                            // Rating (convert to stars)
                            const ratingCell = document.createElement('td');
                            ratingCell.textContent = '★'.repeat(rating.Rating) + '☆'.repeat(5 - rating.Rating);
                            row.appendChild(ratingCell);

                            // Actions
                            const actionCell = document.createElement('td');
                            const deleteBtn = document.createElement('button');
                            deleteBtn.textContent = 'Delete';
                            deleteBtn.addEventListener('click', function() {
                                if (confirm(`Are you sure you want to delete rating for ${rating.ResName}?`)) {
                                    fetch('../actions/delete_rating.php', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/x-www-form-urlencoded',
                                        },
                                        body: `restaurantName=${encodeURIComponent(rating.ResName)}`
                                    })
                                    .then(response => response.json())
                                    .then(result => {
                                        if (result.success) {
                                            row.remove(); // Remove the row from the table
                                            alert('Rating deleted successfully');
                                        } else {
                                            alert('Failed to delete rating');
                                        }
                                    });
                                }
                            });
                            actionCell.appendChild(deleteBtn);
                            row.appendChild(actionCell);

                            tableBody.appendChild(row);
                        });

                        document.getElementById('ratingsModal').style.display = 'flex';
                    } else {
                        alert('Failed to fetch ratings');
                    }
                });
        });

        // Close modal buttons
        document.getElementById('userProfileModalClose').addEventListener('click', function() {
            document.getElementById('userProfileModal').style.display = 'none';
        });

        document.getElementById('ratingsModalClose').addEventListener('click', function() {
            document.getElementById('ratingsModal').style.display = 'none';
        });

        // Logout functionality
        document.getElementById('logoutBtn').addEventListener('click', function() {
            // You'll need to create a logout.php script
            fetch('../actions/logout.php')
                .then(response => {
                    // Redirect to login page
                    window.location.href = '../view/login.php';
                });
        });
    });
    </script>
</body>
</html>

