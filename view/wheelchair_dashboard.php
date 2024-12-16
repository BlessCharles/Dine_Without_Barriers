<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/wheeldash_style.css">
    <title>Wheelchair user dashboard</title>
</head>
<body>
    
    
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

        <!-- The code for the 2 cards that show beneath the header -->
        <div class="content">
            <section class="cards">
                <!-- the code for the user Profile Card -->
                <div class="card" id="userProfileCard">
                    <div class="box">
                        <h3>User Profile</h3>
                    </div>
                    <div class="icon">
                        <img src="../assets/images/1564534_customer_man_user_account_profile_icon.png" alt="Users icon">
                    </div>
                </div>

                <!-- the code for the ratings Card -->
                <div class="card" id="ratingsCard">
                    <div class="box">
                        <h3>My Ratings</h3>
                    </div>
                    <div class="icon">
                        <img src="../assets/images/1564507_checked_favorite_star_favourite_rating_icon.png" alt="ratings icon">
                    </div>
                </div>
            </section>
            <div class="link">
                <a href="../view/wheelchair_user.php">Go to Restaurant page</a>
            </div>
        </div>
        
        
    </div>

    
    <!-- the code for the user Profile Pop-Up Modal -->
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

    <!-- the code for the my Ratings Pop-Up Modal -->
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
                    
                </tbody>
            </table>
            <button id="ratingsModalClose">Close</button>
        </div>
    </div>

    <script>
 document.addEventListener('DOMContentLoaded', function() {
    // the code to ensure modals are hidden initially
    const userProfileModal = document.getElementById('userProfileModal');
    const ratingsModal = document.getElementById('ratingsModal');
    
    userProfileModal.style.display = 'none';
    ratingsModal.style.display = 'none';

    // the code to open User Profile modal and fetch user data
    document.getElementById('userProfileCard').addEventListener('click', function() {
        fetch('../actions/get_user.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('profileFullName').textContent = data.fullName;
                    document.getElementById('profileEmail').textContent = data.email;
                    userProfileModal.style.display = 'flex';
                } else {
                    alert('Failed to fetch user profile');
                }
            });
    });

    // the code to open Ratings modal and fetch ratings
    document.getElementById('ratingsCard').addEventListener('click', function() {
        fetch('../actions/get_user_ratings.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const tableBody = document.getElementById('ratingsTableBody');
                    tableBody.innerHTML = '';

                    data.ratings.forEach(rating => {
                        const row = document.createElement('tr');
                        
                        
                        const nameCell = document.createElement('td');
                        nameCell.textContent = rating.ResName;
                        row.appendChild(nameCell);

                        // the code for the rating (convert to stars)
                        const ratingCell = document.createElement('td');
                        ratingCell.textContent = '★'.repeat(rating.Rating) + '☆'.repeat(5 - rating.Rating);
                        row.appendChild(ratingCell);

                        
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
                                        row.remove();
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

                    ratingsModal.style.display = 'flex';
                } else {
                    alert('Failed to fetch ratings');
                }
            });
    });

    // the code for the close modal buttons
    document.getElementById('userProfileModalClose').addEventListener('click', function() {
        userProfileModal.style.display = 'none';
    });

    document.getElementById('ratingsModalClose').addEventListener('click', function() {
        ratingsModal.style.display = 'none';
    });

    // the code for the logout functionality
    document.getElementById('logoutBtn').addEventListener('click', function() {
        if (confirm('Are you sure you want to logout?')) {
            window.location.href = '../actions/logout.php';
        }
    });

    // the code for the edit Profile Button
    document.getElementById('editProfileBtn').addEventListener('click', function() {
        fetch('../actions/get_user.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // the code to create an edit profile form that matches the existing update script
                    const editForm = `
                        <div id="editProfileModal" class="modal" style="display:flex;">
                            <div class="modal-content">
                                <h2>Edit Profile</h2>
                                <form action="../actions/update_profile.php" method="POST">
                                    <input type="hidden" name="UserID" value="${data.UserID}">
                                
                                    <label for="FirstName">First Name:</label>
                                    <input type="text" id="FirstName" name="FirstName" value="${data.firstName}" required>
                                
                                    <label for="LastName">Last Name:</label>
                                    <input type="text" id="LastName" name="LastName" value="${data.lastName}" required>
                                
                                    <label for="Email">Email:</label>
                                    <input type="email" id="Email" name="Email" value="${data.email}" required>
                                
                                    <label for="UserType">User Type:</label>
                                    <select id="UserType" name="UserType" required>
                                        <option value="WheelchairUser" ${data.userType === 'WheelchairUser' ? 'selected' : ''}>Wheelchair User</option>
                                        <option value="Admin" ${data.userType === 'Admin' ? 'selected' : ''}>Admin</option>
                                        <option value="Restaurant" ${data.userType === 'Restaurant' ? 'selected' : ''}>Restaurant</option>
                                    </select>
                                
                                    <div class="button-container">
                                        <button type="submit">Save Changes</button>
                                        <button type="button" id="cancelEditProfile">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    `;
            
                    // the code to remove any existing edit modal and add new one
                    const existingModal = document.getElementById('editProfileModal');
                    if (existingModal) existingModal.remove();
            
                    document.body.insertAdjacentHTML('beforeend', editForm);
            
                    // the code for the cancel button
                    document.getElementById('cancelEditProfile').addEventListener('click', function() {
                        document.getElementById('editProfileModal').remove();
                    });
                }
            });
    });

    // the code for the delete Account Button
    document.getElementById('deleteAccountBtn').addEventListener('click', function() {
        if (confirm('Are you sure you want to delete your account? This action cannot be undone.')) {
            
            window.location.href = '../actions/delete_profile.php';
        }
    });
});

    </script>
</body>
</html>

