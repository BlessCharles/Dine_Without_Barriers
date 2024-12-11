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
    header('Location: view/login.html');
    exit;
}

//The code to fetch the user's data from the session
$user_id = $_SESSION['UserID'];
$email = $_SESSION['Email'];
$role = $_SESSION['UserType'];

//The code for a query to fetch all users from the database
$query = "SELECT UserID, FirstName, LastName, Email, UserType FROM DWB_Users";
$result = $conn->query($query);
?>



<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/users_style.css">
    <title>User Management</title>
</head>
<body>

    <!--The code for the side bar on the page where the navigation for dashboard, user and recipe management can be found-->
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

    <!--The code for the top section where the user management name and background design appear-->
    <header class="top">
        <div class="top-section">
            <h1>User Management</h1>
                
        </div>
    
    </header>

    <!--The code for the table section where a table with four columns can be found-->
    <section class="table-section">
        <table>
            <tr>
                
                <th>Name</th>
                <th>Email</th>
                
                <th>Actions</th>
            </tr>
        
            <?php
            // Check if users exist
            if ($result->num_rows > 0) {
                // Loop through all users and populate the table rows
                while ($user = $result->fetch_assoc()) {
                    echo '<tr>';
                    
                    echo '<td>' . $user['FirstName'] . ' ' . $user['LastName'] . '</td>';
                    echo '<td>' . $user['Email'] . '</td>';
                    echo '<td>
                            <a href="#view' . $user['UserID'] . '" class="btn">View</a>
                            <a href="#update' . $user['UserID'] . '" class="btn">Update</a>
                            <a href="../actions/delete_user.php?UserID=' . $user['UserID'] . '" class="btn" onclick="return confirm(\'Are you sure you want to delete this user?\');">Delete</a>
                        </td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="4">No users found</td></tr>';
            }
            ?>
        </table>
    </section>

    <!-- The code for the popups for View, Update for each user -->
    <?php
    if ($result->num_rows > 0) {
        $result->data_seek(0);
        while ($user = $result->fetch_assoc()) {
            echo '<section id="view' . $user['UserID'] . '" class="popup">
                    <div class="popup-content">
                        <h2>User Details</h2>
                        <p><b>Name:</b> ' . $user['FirstName'] . ' ' . $user['LastName'] . '</p>
                        <p><b>Email:</b> ' . $user['Email'] . '</p>
                        <p><b>UserType:</b> ' . $user['UserType'] . '</p>
                        
                        <a href="#" class="btn">Close</a>
                    </div>
                </section>';
            echo '<section id="update' . $user['UserID'] . '" class="popup">
                    <div class="popup-content">
                        <h2>Update User Details</h2>
                        <form id="updateForm' . $user['UserID'] . '" class="updateForm" action="../actions/update_user.php" method="POST">
                            <input type="hidden" name="UserID" value="' . $user['UserID'] . '">
                            <label for="FirstName' . $user['UserID'] . '">First Name:</label>
                            <input type="text" name="FirstName" value="' . $user['FirstName'] . '" required><br><br>
        
                            <label for="LastName' . $user['UserID'] . '">Last Name:</label>
                            <input type="text" name="LastName" value="' . $user['LastName'] . '" required><br><br>
        
                            <label for="UserType' . $user['UserID'] . '">User Type:</label>
                            <input type="text" name="UserType" value="' . $user['UserType'] . '" required><br><br>
        
                            <label for="Email' . $user['UserID'] . '">Email:</label>
                            <input type="email" name="Email" value="' . $user['Email'] . '" required><br><br>
        
                            <button type="submit" class="save-btn">Save Changes</button>
                        </form>
                    </div>
                </section>';
        
        }
    }
    ?>

    <script>
    function closePopup() {
        document.querySelectorAll('.popup').forEach(popup => popup.style.display = 'none');
    }
    </script>

</body>
</html>

<?php
// The code to close the database connection at the end
$conn->close();
?>