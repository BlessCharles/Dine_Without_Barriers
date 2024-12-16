<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login Page</title>
        <link rel="stylesheet" href="../assets/login_style.css">
    </head>
    <body>
        <div class="login-container">
            <p class="title"><b>Login</b></p>
            <!--The code for the form for the login-->
            <form id="loginForm" action="../actions/login_user.php" method="POST" onsubmit="return formvalidate()">
                <div class="form-control">
                    <span class="email-icon">&#9993;</span>
                    <input type="email" id="email" placeholder="Enter your email" name="email">
                    <p id="emailError" class="error"></p>
                </div>

                <div class="form-control">
                    <span class="password-icon">&#128274;</span>
                    <input type="password" id="password" placeholder="Enter your password" name="password">
                    <p id="passwordError" class="error"></p>
                </div>

            
                <!--The code for the log in button and sign up redirection-->
                <button type="submit" class="login-button"><b>Log in</b></button>
            
                <p class="signup-text">Don't have an account? <a href="../view/register.php">Register</a></p>
            </form>
        </div>

        <script>
        function formvalidate() {
            var email = document.getElementById("email").value;
            var password = document.getElementById("password").value;
            var errorMessages = [];
            var passwordError = document.getElementById("passwordError");
            var emailError = document.getElementById("emailError");

            passwordError.textContent = "";
            emailError.textContent = "";

            //The code for email validation using regex
            var emailPattern = /^[a-zA-Z0-9._%+-]+@(ashesi\.edu\.gh|gmail\.com|yahoo\.com|outlook\.com)$/;
            if (email === "") {
                errorMessages.push("Email is required!");
                emailError.textContent = "Email is required!";
            } else if (!emailPattern.test(email)) {
                errorMessages.push("Invalid email format");
                emailError.textContent = "Invalid email format";
            }

            //the code for password validationusing regex
            if (password.length < 8) {
                errorMessages.push("Password must be at least 8 characters!");
                passwordError.textContent = "Password must be at least 8 characters!";
            }
            if (!/[A-Z]/.test(password)) {
                errorMessages.push("Password must contain at least one uppercase letter!");
                passwordError.textContent = "Password must contain at least one uppercase letter!";
            }
            if ((password.match(/\d/g) || []).length < 3) {
                errorMessages.push("Password must include at least three digits!");
                passwordError.textContent = "Password must include at least three digits!";
            }
            if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
                errorMessages.push("Password must contain at least one special character!");
                passwordError.textContent = "Password must contain at least one special character!";
            }

            if (errorMessages.length > 0) {
                return false;
            }
            return true;
        }
        </script>
    </body>
</html>