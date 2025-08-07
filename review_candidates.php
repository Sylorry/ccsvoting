<?php
// Start the session
session_start();
// Database connection details
$servername = "127.0.0.1";
$username = "u878574291_ccs1";
$password = "CCSPseudocode01";
$dbname = "u878574291_ccs";
// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Retrieve student_id from session
$student_id = isset($_SESSION['student_id']) ? $_SESSION['student_id'] : "Not logged in";
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["confirm_vote"])) {
    if ($student_id === "Not logged in") {
        die("Student ID not found. Please log in again.");
    }
    // Store the final vote in session (optional, for verification)
    if (isset($_SESSION['selected_votes']) && is_array($_SESSION['selected_votes'])) {
        $_SESSION['final_votes'] = $_SESSION['selected_votes'];
    } else {
        die("No votes selected.");
    }
    // Prepare SQL update statement for vote_tally in student_candidates table
    $stmt = $conn->prepare("UPDATE student_candidates SET vote_tally = vote_tally + 1 WHERE student_id = ?");
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    // Loop through selected votes and update vote_tally in the database
    foreach ($_SESSION['selected_votes'] as $position => $candidate_ids) {
        foreach ($candidate_ids as $candidate_id) {
            $stmt->bind_param("s", $candidate_id);
            if (!$stmt->execute()) {
                die("Error updating vote_tally: " . $stmt->error);
            }
        }
    }
    // Update the has_voted field for the student
    $update_query = "UPDATE students SET has_voted = 1 WHERE student_id = ?";
    $update_stmt = $conn->prepare($update_query);
    if (!$update_stmt) {
        die("Error preparing update statement: " . $conn->error);
    }
    $update_stmt->bind_param("s", $student_id);
    if (!$update_stmt->execute()) {
        die("Error updating has_voted status: " . $update_stmt->error);
    }
    // Close the statements and connection
    $stmt->close();
    $update_stmt->close();
    $conn->close();
    // Redirect to thank_you.php
    header("Location: thank_you.php");
    exit();
}

// Function to get candidate name by student_id
function getCandidateName($conn, $student_id) {
    $stmt = $conn->prepare("SELECT candidate_name FROM student_candidates WHERE student_id = ?");
    if (!$stmt) {
        return "Unknown Candidate";
    }
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        return $row['candidate_name'];
    } else {
        return "Unknown Candidate";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Your Vote</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #2c3e50, #27ae60);
            color: #333; 
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            max-width: 700px;
            background: #ffffff;
            color: #333;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        h1 {
            color: #388E3C;
            font-weight: bold;
        }
        .review-section {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 6px;
            background: #C8E6C9;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .position-title {
            font-weight: bold;
            font-size: 1.2em;
            color: #2E7D32;
        }
        .candidate-name {
            font-size: 1.1em;
            color: #1B5E20;
        }
        .btn-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .btn-back, .btn-submit {
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            text-transform: uppercase;
            width: 48%;
            text-align: center;
            text-decoration: none;
        }
        .btn-back {
            background: #FFB300;
        }
        .btn-submit {
            background: #4CAF50;
        }
        .btn-back:hover {
            background: #FFA000;
        }
        .btn-submit:hover {
            background: #388E3C;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Review Your Vote</h1>
        <p>Please review your selected candidates before finalizing your vote.</p>
        <?php if (!empty($_SESSION['selected_votes'])): ?>
            <?php foreach ($_SESSION['selected_votes'] as $position => $candidate_ids): ?>
                <div class="review-section">
                    <div class="position-title"><?php echo htmlspecialchars($position); ?></div>
                    <?php foreach ($candidate_ids as $candidate_id): ?>
                        <?php $candidate_name = getCandidateName($conn, $candidate_id); ?>
                        <div class="candidate-name">✔ <?php echo htmlspecialchars($candidate_name); ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No votes selected. Please go back and vote.</p>
        <?php endif; ?>
        <form method="post">
            <div class="btn-container">
                <a class="btn-back" onclick="window.history.back()">Change Vote</a>
                <button type="submit" name="confirm_vote" class="btn-submit">Confirm Vote</button>
            </div>
        </form>
    </div>
    <script>
        // Log the selected candidate_ids to the console
        let selectedCandidateIds = <?php echo json_encode($_SESSION['selected_votes'] ?? []); ?>;
        console.log("Selected Candidate IDs:", selectedCandidateIds);
        // Log the student_id to the console
        let student_id = <?php echo json_encode($student_id); ?>;
        console.log("Student ID:", student_id);
    </script>
</body>
</html>