<?php
session_start();
// Database connection
$servername = "127.0.0.1";
$username = "u878574291_ccs1";
$password = "CCSPseudocode01";
$database = "u878574291_ccs";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
date_default_timezone_set('Asia/Manila');

// Initialize an error message variable
$error_message = "";

// Fetch election details
$election_query = "SELECT * FROM election LIMIT 1"; // Assuming only one election is active at a time
$election_result = $conn->query($election_query);

if ($election_result->num_rows > 0) {
    $election = $election_result->fetch_assoc();
    $start_time = strtotime($election['start_time']);
    $end_time = strtotime($election['end_time']);
    $enable = $election['enable'];
    $date = $election['date'];
    $current_time = time();
    $current_date = date('Y-m-d');

    // Debugging: Check the value of $enable
    echo "<!-- Debug: enable = $enable -->";

    // Check if the election is enabled and the current time is within the election period
    if ($enable == 0) {
    $error_message = "The election is disabled.";
    } elseif ($current_date < $date) {
    $error_message = "The election has not started yet."; // Future date
    } elseif ($current_date == $date) {
    if ($current_time < $start_time) {
        $error_message = "The election has not started yet."; // Before start time
    } elseif ($current_time > $end_time) {
        $error_message = "The election has ended."; // After end time
    }
    } elseif ($current_date > $date) {
    $error_message = "The election has ended."; // Past date
    } else {
    $error_message = "No election found.";
    }
}

// Handling form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($error_message)) {
    $student_id = $_POST['student_id'];

    // Use prepared statements for security
    $stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ?");
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();

        // Check if the student has already voted
        if ($student['has_voted'] == 1) { 
            $error_message = "You have already voted. You cannot vote again.";
        } else {
             $_SESSION['student_id'] = $student_id;
            // Redirect to voting.php after submission
            header("Location: voting.php?student_id=$student_id");
            exit();
        }
    } else {
        // Set an error message if the student ID does not exist
        $error_message = "Student ID does not exist. Please try again.";
    }

    $stmt->close();
}

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CCS Online Voting System</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script>
        console.log("Start Time: <?php echo $start_time; ?>");
        console.log("End Time: <?php echo $end_time; ?>");
        console.log("Current Time: <?php echo $current_time; ?>");
    </script>
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

        .logo-container {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-container img {
            height: 70px;
        }

        .logo-container .logo-text {
            font-size: 1.5rem;
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

        .vote-button {
            background-color: #ffcc00;
            color: #333;
            border: none;
            border-radius: 30px;
            padding: 15px 30px;
            font-size: 1.2rem;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }

        .vote-button:hover {
            background-color: #e6b800;
            transform: scale(1.05);
        }

        /* Form Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: white;
            color: #333;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        .modal-content input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .modal-content button {
            background-color: #43a047;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .modal-content button:hover {
            background-color: #388e3c;
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
        .vote-button:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
            opacity: 0.7;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <div class="navbar">
        <div class="logo-container">
            <img src="ccsvoting/ccs.png" alt="Logo">
            <span class="logo-text">CCS PSEUDOCODE.COM SOCIETY</span>
        </div>
        <div class="nav-buttons">
            <button class="nav-button" onclick="window.location.href='candidates1.php'">
                <i class="fas fa-users"></i> View Candidates
            </button>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="hero">
        <div class="hero-content">
            <h1>Online Voting System</h1>
            <p>"Empowering the CCS department with secure and seamless online voting!"</p>
            <button 
    class="vote-button" 
    <?php 
            if ($enable == 0 || $current_date < $date || $current_date > $date || 
                ($current_date == $date && ($current_time < $start_time || $current_time > $end_time))) {
                echo 'disabled="disabled"';
            }
            ?>
    onclick="openModal()"
>
    <i class="fas fa-vote-yea"></i> Vote Now
</button>

            <?php if ($error_message): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>
        </div>
        <div class="hero-illustration">
            <img src="ccsvoting/123.png" alt="Illustration of voting system">
        </div>
    </div>

    <!-- Modal -->
    <div class="modal" id="voteModal">
        <div class="modal-content">
            <h2>Fill Your Details</h2>
            <form method="POST">
                <input type="number" name="student_id" placeholder="Student ID" required>
                <button type="submit">
                    <i class="fas fa-arrow-right"></i> Proceed to Vote
                </button>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('voteModal').style.display = 'flex';
        }
    </script>
</body>
</html>