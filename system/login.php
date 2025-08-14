<?php
session_start(); // Start the session

// Define maximum login attempts and lockout duration
$max_attempts = 3;
$lockout_duration = 300; // 5 minutes in seconds

// Check if the user is already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: index.php"); // Redirect to the main page if already logged in
    exit();
}

// Initialize login attempts if not set
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

// Check if the user is locked out
if (isset($_SESSION['lockout_time']) && time() < $_SESSION['lockout_time']) {
    $error = "Too many login attempts. Please try again later.";
} else {
    // Handle login form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $remember_me = isset($_POST['remember_me']); // Check if "Remember Me" is checked

        // Replace with your actual admin credentials
        $admin_username = "admin"; 
        $admin_password = "ccs.dlsjbc"; // Use a secure password in production

        // Check credentials
        if ($username === $admin_username && $password === $admin_password) {
            $_SESSION['admin_logged_in'] = true; // Set session variable
            $_SESSION['login_attempts'] = 0; // Reset attempts on successful login

            // Set cookie if "Remember Me" is checked
            if ($remember_me) {
                setcookie('username', $username, time() + (86400 * 30), "/"); // 30 days
                setcookie('password', $password, time() + (86400 * 30), "/"); // 30 days
            } else {
                // Clear cookies if not checked
                setcookie('username', '', time() - 3600, "/");
                setcookie('password', '', time() - 3600, "/");
            }

            header("Location: index.php"); // Redirect to the main page
            exit();
        } else {
            $_SESSION['login_attempts']++; // Increment login attempts
            if ($_SESSION['login_attempts'] >= $max_attempts) {
                $_SESSION['lockout_time'] = time() + $lockout_duration; // Set lockout time
                $error = "Too many failed attempts. Please try again later.";
            } else {
                $error = "Invalid username or password.";
            }
        }
    } else {
        // Pre-fill the username and password if cookies are set
        if (isset($_COOKIE['username'])) {
            $username = $_COOKIE['username'];
        }
        if (isset($_COOKIE['password'])) {
            $password = $_COOKIE['password'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background: linear-gradient(to right, #0f2027, #203a43, #2c5364); /* Gradient background */
            color: white;
        }

        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            padding: 10px 15px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            text-decoration: none;
            font-size: 1.2em;
            transition: background 0.3s;
        }

        .back-button:hover {
            background-color: #2980b9;
        }

        .profile-section {
            text-align: center;
            margin-bottom: 20px;
            animation: slideIn 1s;
        }

        @keyframes slideIn {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .profile-section h1 {
            font-size: 4em; /* Adjusted size */
            margin-bottom: 10px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.7);
        }

        .profile-section p {
            font-size: 1.2em;
            text-shadow: 0 1px 5px rgba(0, 0, 0, 0.5);
        }

        .login-container {
            background: rgba(0, 110, 46, 0.9);
            padding: 40px 30px; /* Adjusted padding */
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
            text-align: center;
            width: 400px;
            animation: bounceIn 1s;
            display: flex; /* Use flexbox */
            flex-direction: column; /* Stack children vertically */
            align-items: center; /* Center children horizontally */
            margin: 0 auto; /* Center the login container */
        }

        @keyframes bounceIn {
            0% { transform: scale(0); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        .login-container h2 {
            font-size: 2.5em; /* Increased size */
            color: white;
            margin-bottom: 20px; /* Added margin */
        }

        .input-container {
            position: relative;
            margin: 20px 0;
            width: 100%; /* Make input container full width */
            max-width: 350px; /* Set a max width for centering */
        }

        .input-label {
            position: absolute;
            left: 20px;
            top: 15px;
            transition: 0.2s ease all;
            color: gray;
            font-size: 1.2em;
        }

        input {
            padding: 15px 20px; /* Add padding for top/bottom and left/right */
            padding-right: 50px; /* Add padding for the icon */
            width: calc(100% - 40px); /* Full width minus padding */
            border: none;
            border-radius: 25px;
            font-size: 1.2em;
            transition: border 0.3s, box-shadow 0.3s; /* Added box-shadow transition */
            margin: 0 auto; /* Center the input field */
            display: block; /* Ensure the input is a block element */
        }

        input:focus {
            border: 2px solid #0ad82a;
            box-shadow: 0 0 5px rgba(10, 216, 42, 0.5); /* Added shadow on focus */
        }

        input:focus + .input-label,
        input:not(:placeholder-shown) + .input-label {
            top: -10px;
            left: 30px;
            font-size: 15px;
            color: black;
            background-color: white;
            border-radius: 10px;
            padding: 1px;
            margin-left: 5px;
        }

        .toggle-password {
            position: absolute;
            right: 20px; /* Position the icon inside the input */
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: gray;
            z-index: 1; /* Ensure the icon is above the input */
        }

        button {
            padding: 15px;
            background: #0ad82a;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            width: 100%;
            font-size: 1.2em;
            margin-top: 20px;
            transition: background 0.3s, transform 0.3s; /* Added transform transition */
        }

        button:hover {
            background: #1c7fcc;
            transform: translateY(-2px); /* Slight lift effect on hover */
        }

        .remember-me {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }

        input[type="checkbox"] {
            width: 20px;
            height: 20px;
            margin-right: 10px;
        }

        .error-message {
            color: red;
            margin-top: 10px;
            font-size: 1em; /* Adjusted size */
        }

        .logo {
            width: 150px; /* Set logo width */
            margin-bottom: 20px; /* Space below the logo */
        }
    </style>
</head>
<body>
    
    <a href="https://eventcalendar.ccspseudocode.com/admin.php" class="back-button">Back</a>

    <div class="profile-section">
        <h1>CCS STUDENTS' PROFILE</h1>
        <p>Welcome to the CCS Students' Profile section. Here you can manage student profiles.</p>
    </div>

    <img src="system/ccs.png" alt="Logo" class="logo"> <!-- Replace with your logo path -->

    <div class="login-container">
        <h2>Admin Login</h2>
        <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>
        <form method="post" action="">
            <div class="input-container">
                <input type="text" name="username" id="username" placeholder=" " value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" required>
                <label for="username" class="input-label">Username</label>
            </div>
            <div class="input-container">
                <input type="password" name="password" id="password" placeholder=" " value="<?php echo isset($password) ? htmlspecialchars($password) : ''; ?>" required>
                <label for="password" class="input-label">Password</label>
                <i class="fas fa-eye toggle-password" id="togglePassword" onclick="togglePasswordVisibility()"></i>
            </div>
            <div class="remember-me">
                <input type="checkbox" name="remember_me" id="remember_me" <?php echo isset($_COOKIE['username']) ? 'checked' : ''; ?>>
                <label for="remember_me">Remember Me</label>
            </div>
            <button type="submit">
                <i class="fas fa-sign-in-alt button-icon"></i> LOG IN
            </button>
        </form>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('togglePassword');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>