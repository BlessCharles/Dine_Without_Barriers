<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register Page</title>
    <link rel="stylesheet" href="../assets/register_style.css">
</head>

<body>
    <div class="register-container">
        <p class="title"><b>Register</b></p>

        <form id="signupForm" action="../actions/register_user.php" method="POST" onsubmit="return validateForm()">
            <!-- First name and last name in the same row -->
            <div class="form-control form-control-row">
                <div class="form-control">
                    <span class="icon">&#128100;</span>
                    <input type="text" id="firstname" name="firstname" placeholder="Enter your First name">
                    <div class="error" id="firstnameError"></div>
                </div>
    
                <div class="form-control">
                    <span class="icon">&#128100;</span>
                    <input type="text" id="lastname" name="lastname" placeholder="Enter your Last name">
                    <div class="error" id="lastnameError"></div>
                </div>
            </div>
    
            <!-- Email on the next line -->
            <div class="form-control">
                <span class="icon">&#9993;</span>
                <input type="email" id="email" placeholder="Enter your email" name="email">
                <div class="error" id="emailError"></div>
            </div>
    
            <!-- Password and confirm password on the same line -->
            <div class="form-control form-control-row">
                <div class="form-control">
                    <span class="icon">&#128274;</span>
                    <input type="password" id="password" placeholder="Enter your password" name="password">
                    <div class="error" id="passwordError"></div>
                </div>
    
                <div class="form-control">
                    <span class="icon">&#128274;</span>
                    <input type="password" id="confirmPassword" placeholder="Confirm your password" name="confirmPassword">
                    <div class="error" id="confirmPasswordError"></div>
                </div>
            </div>

            <div class="form-control">
                <label for="restaurantQuestion">Are you a restaurant?</label>
                <select id="restaurantQuestion" name="restaurantQuestion" required onchange="toggleWheelchairQuestion()">
                    <option value="" disabled selected>Select an option</option>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
            </div>
    
            <!-- If the answer to restaurant is 'No', ask if the user is a wheelchair user -->
            <div class="form-control" id="wheelchairQuestion" style="display: none;">
                <label for="wheelchairUser">Are you a wheelchair user?</label>
                <select id="wheelchairUser" name="wheelchairUser" onchange="toggleBackToRestaurant()">
                    <option value="" disabled selected>Select an option</option>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
            </div>
    
            <button type="submit" id="submitBtn"><b>Sign Up</b></button>
    
            <p class="login-link">Already have an account? <a href="../view/login.php">Log in</a></p>
        </form>
    </div>
    

    <script>
    function validateForm() {
        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value.trim();
        const confirmPassword = document.getElementById("confirmPassword").value.trim();
        const errorMessages = [];
        const passwordError = document.getElementById("passwordError");
        const successMessage = document.getElementById("successMessage");

        // Clear previous messages
        passwordError.innerHTML = "";
        if (successMessage) successMessage.textContent = "";

        // Email pattern for Ashesi emails
        const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;


        // Email validation
        if (email === "") {
            errorMessages.push("Email is required!");
        } else if (!emailPattern.test(email)) {
            errorMessages.push("Invalid email format.");
        }

        // Password validations
        if (password.length < 8) {
            errorMessages.push("Password must be at least 8 characters long!");
        }
        if (!/[A-Z]/.test(password)) {
            errorMessages.push("Password must contain at least one uppercase letter!");
        }
        if ((password.match(/\d/g) || []).length < 3) {
            errorMessages.push("Password must include at least three digits!");
        }
        if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
            errorMessages.push("Password must contain at least one special character!");
        }

        // Confirm password match
        if (password !== confirmPassword) {
            errorMessages.push("Passwords do not match.");
        }

        // Show errors or success message
        if (errorMessages.length > 0) {
            passwordError.innerHTML = errorMessages.join("<br>");
            return false;
        } else {
            if (successMessage) {
                successMessage.textContent = "Registered successfully!";
                setTimeout(function () {
                    successMessage.textContent = "";
                }, 20000);
            }
            return true;
        }
    }
    // Function to display the wheelchair user question if 'No' is selected for restaurant question
    function toggleWheelchairQuestion() {
        const restaurantQuestion = document.getElementById("restaurantQuestion").value;
        const wheelchairQuestion = document.getElementById("wheelchairQuestion");

        // If 'No' is selected for restaurant, show wheelchair question
        if (restaurantQuestion === "no") {
            wheelchairQuestion.style.display = "block";
        } else {
            wheelchairQuestion.style.display = "none";
        }
    }

    // Function to hide the wheelchair question and return to the restaurant question
    function toggleBackToRestaurant() {
        const wheelchairUser = document.getElementById("wheelchairUser").value;
        const wheelchairQuestion = document.getElementById("wheelchairQuestion");
        const restaurantQuestion = document.getElementById("restaurantQuestion");

        // If 'No' is selected for wheelchair user, hide wheelchair question and reset to restaurant question
        if (wheelchairUser === "no") {
            wheelchairQuestion.style.display = "none";
            restaurantQuestion.value = "no"; // Reset restaurant question to 'No'
        }
    }

    
    </script>
</body>
</html>