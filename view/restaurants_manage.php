<?php
//The code to connect to the database
include '../db/config.php';

//The code to enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

//The code to start session
session_start();

// The code to redirect user to login page if not logged in
if (!isset($_SESSION['UserID'])) {
    header('Location: login.php');
    exit;
}

//The code for a query to fetch pending restaurants from the database
$query = "SELECT p.PendingID, p.ResName, p.ResAddress, p.PhoneNumber, p.AccessibilityFeatures, p.RestaurantImage, u.FirstName, u.LastName 
        FROM DWB_Restaurant_Pending p
        JOIN DWB_Users u ON p.UserID = u.UserID";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/users_style.css">
    <title>Restaurant Management</title>
</head>
<body>

    <!--The code for the side bar on the page where the navigation for dashboard, user and recipe management can be found-->
    <div class="side-bar">
        <div class="brand-name">
            <h3>DWB</h3>
        </div>
        <ul>
            <!-- the code for the dashboard with a link -->
            <li>
                <a href="../view/dashboard.php">
                    <img src="../assets/images/9055226_bxs_dashboard_icon.png">
                    <p><span>Dashboard</span></p>
                </a>
            </li>
    
            <!-- the code for the user Management with a link -->
            <li>
                <a href="../view/users.php">
                    <img src="../assets/images/8665306_circle_user_icon.png">
                    <p><span>User Management</span></p>
                </a>
            </li>
    
            <!-- the code for the recipe Management with a link -->
            <li>
                <a href="../view/restaurants_manage.php">
                    <img src="../assets/images/2639899_restaurant_icon.png">
                    <p><span>Restaurant Management</span></p>
                </a>
            </li>
        </ul>
    </div>

    <!--The code for the top section where the user management name and background design appear-->
    <header class="top">
        <div class="top-section">
            <h1>Restaurant Management</h1>
        </div>
    </header>

    <!--The code for the table section where a table with six columns can be found-->
    <section class="table-section">
        <table>
            <tr>
                <th>Submitted By</th>
                <th>Name</th>
                <th>Location</th>
                <th>Phone Number</th>
                <th>Features</th>
                <th>Actions</th>
            </tr>
        
            <?php
            // the code to check if pending restaurants exist
            if ($result->num_rows > 0) {
                // the code to loop through all pending restaurants and populate the table rows
                while ($restaurant = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($restaurant['FirstName'] . ' ' . $restaurant['LastName']) . '</td>';
                    echo '<td>' . htmlspecialchars($restaurant['ResName']) . '</td>';
                    echo '<td>' . htmlspecialchars($restaurant['ResAddress']) . '</td>';
                    echo '<td>' . htmlspecialchars($restaurant['PhoneNumber']) . '</td>';
                    echo '<td>' . htmlspecialchars($restaurant['AccessibilityFeatures']) . '</td>';
                
                    echo '<td>
                            <form action="../actions/approve_restaurant.php" method="post" style="display:inline;">
                                <input type="hidden" name="pendingId" value="' . htmlspecialchars($restaurant['PendingID']) . '">
                                <button type="submit" name="action" value="approve" class="btn">Approve</button>
                            </form>
                            <form action="../actions/approve_restaurant.php" method="post" style="display:inline;">
                                <input type="hidden" name="pendingId" value="' . htmlspecialchars($restaurant['PendingID']) . '">
                                <button type="submit" name="action" value="disapprove" class="btn">Disapprove</button>
                            </form>
                        </td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="6">No pending restaurants found</td></tr>';
            }
            ?>
        </table>
    </section>

    <script>
    function closePopup() {
        document.querySelectorAll('.popup').forEach(popup => popup.style.display = 'none');
    }
    </script>

</body>
</html>

<?php

$conn->close();
?>