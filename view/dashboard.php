<?php
// The code to connect to the database
include '../db/config.php';

//The code to enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// The code to start session
session_start();

//The code to redirect user to login page if not logged in
if (!isset($_SESSION['UserID'])) {
    header('Location:view/login.html');
    exit;
}

//The code to fetch the user's data from the session
$user_id = $_SESSION['UserID'];
$email = $_SESSION['Email'];
$role = $_SESSION['UserType'];

//The code for the query to get total users, recipes, and active users
$totalUsersQuery = "SELECT COUNT(*) FROM DWB_Users";
$totalUsersResult = $conn->query($totalUsersQuery);
$totalUsers = $totalUsersResult->fetch_row()[0];

$totalRatingsQuery = "SELECT COUNT(*) FROM DWB_Ratings";
$totalRatingsResult = $conn->query($totalRatingsQuery);
$totalRatings = $totalRatingsResult->fetch_row()[0];

$activeUsersQuery = "SELECT COUNT(*) FROM DWB_Users  ";
$activeUsersResult = $conn->query($activeUsersQuery);
$activeUsers = $activeUsersResult->fetch_row()[0];
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/dash_style.css">
    <title>Dashboard</title>
</head>
<body>
    <!--The code to create the nagivation menu to the left of the page-->
    <div class="side-bar">
        <div class="brand-name">
            <h3>DWB</h3>
        </div>
        <ul>
            <!-- Dashboard with a link -->
            <li>
                <a href="../view/dashboard.php">
                    <img src="../assets/images/9055226_bxs_dashboard_icon.png">
                    <p><span>Dashboard</span></p>
                </a>
            </li>
    
            <!-- User Management with a link -->
            <li>
                <a href="../view/users.php">
                    <img src="../assets/images/8665306_circle_user_icon.png">
                    <p><span>User Management</span></p>
                </a>
            </li>
    
            <!-- Recipe Management with a link -->
            <li>
                <a href="../view/restaurants_manage.php">
                    <img src="../assets/images/2639899_restaurant_icon.png">
                    <p><span>Restaurant Management</span></p>
                </a>
            </li>
        </ul>
    </div>
    
    <!--The code for the top navigation bar (where the search box and the navigation icon is located)-->
    <div class="container">
        <header class="top-section">
            <div class="navigation">
                <div class="branding">
                    <img src="../assets/images/DIne_without_Barrirs-removebg-preview.png" alt="Dine Without Barriers Logo" class="logo">
                    <h1 class="name">Dine Without Barriers</h1>
                </div>
                <div class="notification">
                    
                    <a href="../actions/logout.php">Log out</a>
                </div>
            </div>
        </header>
        
        <!--The code for the 4 cards that show beneath the header-->
        <div class="content">
            <!--This code is for the number, a short description and the icon on the card-->
            <section class="cards">
                <!--This code is for the first card with information about the total users-->
                <div class="card">
                    <div class="box">
                        <h1><?php echo $totalUsers; ?></h1>
                        <h3>Total Users</h3>
                    </div>
                    <div class="icon">
                        <img src="../assets/images/309041_users_group_people_icon.png" alt="Users icon">
                    </div>
                </div>
                
                
                
                <!--This code is for the third card with information about the delete icon-->
                <div class="card">
                    <div class="box">
                        <h1><?php echo $totalRatings; ?></h1>
                        <h3>Ratings</h3>
                    </div>
                    <div class="icon">
                        <img src="../assets/images/1564507_checked_favorite_star_favourite_rating_icon.png" alt="Ratings icon">
                    </div>
                </div>
    
                <!-- This code is for the fourth card with information about the active users-->
                <div class="card">
                    <div class="box">
                        <h1><?php echo $activeUsers; ?></h1>
                        <h3>Active users</h3>
                    </div>
                    <div class="icon">
                        <img src="../assets/images/8665306_circle_user_icon.png" alt="Active Users icon">
                    </div>
                </div>
            </section>
        </div>
    </div>
</body>
</html>