<?php

// Database connection
$servername = "127.0.0.1";
$username = "u878574291_ccs1";
$password = "CCSPseudocode01";
$database = "u878574291_ccs";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize an error message variable
$error_message = "";

// Handling form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);

    // Check if the student ID exists in the "students" table
    $query = "SELECT * FROM students WHERE student_id = '$student_id'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();

        // Check if the student has already voted
        if ($student['has_voted'] == 1) {
            $error_message = "You have already voted. You cannot vote again.";
        } else {
            // Redirect to voting.php after submission
            header("Location: voting.php?student_id=$student_id");
            exit();
        }
    } else {
        // Set an error message if the student ID does not exist
        $error_message = "Student ID does not exist. Please try again.";
    }

    // Close the database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CCS Online Voting System</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* General Styles */
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #43a047, #66bb6a); /* Green gradient */
            color: white;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Navigation Bar */
        .navbar {
            display: flex;
            justify-content: space-between;
            padding: 20px 40px;
            align-items: center;
            background: linear-gradient(135deg, #041602, #1d7f13);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .navbar img {
            height: 70px;
        }

        .navbar .logo {
            font-size: 2rem;
            font-weight: bold;
            color: white;
        }

        .navbar .nav-buttons {
            display: flex;
            gap: 20px;
        }

        .navbar .nav-button {
            background-color: #ffcc00;
            color: #333;
            border: none;
            border-radius: 30px;
            padding: 10px 20px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            display: flex;
            align-items: center;
            gap: 10px; /* Space between icon and text */
        }

        .navbar .nav-button:hover {
            background-color: #e6b800;
            transform: scale(1.05);
        }

        /* Hero Section */
        .hero {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 40px;
            flex: 1;
        }

        .hero-content {
            max-width: 50%;
            margin: 40px auto;
            padding: 20px;
        }

        .hero-content h1 {
            font-size: 6rem;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(5, 0, 0, 0.5);
        }

        .hero-content p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }

        /* Error Message */
        .error-message {
            color: red;
            margin-top: 10px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero {
                flex-direction: column;
                text-align: center;
            }

            .hero-content, .hero-illustration {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <div class="navbar">
        <img src="ccsvoting/ccs.png" alt="Logo">
        <div class="logo">CCS PSEUDOCODE.COM SOCIETY</div>
        <div class="nav-buttons">
            <button class="nav-button" title="Add new candidates" onclick="window.location.href='add_candidates.php'">
                <i class="fas fa-user-plus"></i> Add Candidates
            </button>
            <button class="nav-button" title="View the list of candidates" onclick="window.location.href='candidates.php'">
                <i class="fas fa-list"></i> View Candidates
            </button>
            <button class="nav-button" title="Access the admin panel" onclick="window.location.href='admin.php'">
                <i class="fas fa-cog"></i> Admin Panel
            </button>
            <button class="nav-button" title="View the vote tally results" onclick="window.location.href='results.php'">
                <i class="fas fa-chart-bar"></i> View Vote Tally
            </button>
            <button class="nav-button" title="Logout" onclick="window.location.href='https://eventcalendar.ccspseudocode.com/admin.php'">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="hero">
        <div class="hero-content">
            <h1>Online Voting System</h1>
            <p>"Empowering the CCS department with secure and seamless online voting!"</p>
            <?php if ($error_message): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>
        </div>
        <div class="hero-illustration">
            <img src="ccsvoting/123.png" alt="Illustration of voting system">
        </div>
    </div>

    <script>
        // No modal function needed since the button is removed
    </script>
</body>
</html>