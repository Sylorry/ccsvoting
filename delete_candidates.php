<?php
// delete_candidates.php - Modified to handle selective deletion

session_start();

// Database connection details
$servername = "localhost"; // or 127.0.0.1
$username = "root";        // default XAMPP username
$password = "";            // default XAMPP password is empty
$database = "voting";         // make sure this DB exists in phpMyAdmin

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if we're deleting a specific candidate or all candidates
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Delete a specific candidate
    $candidate_id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM student_candidates WHERE student_id = ?");
    $stmt->bind_param("i", $candidate_id);
    $stmt->execute();
    $stmt->close();
    
    // Set success message
    $_SESSION['message'] = "Candidate deleted successfully.";
    $_SESSION['message_type'] = "success";
} else if (isset($_GET['all']) && $_GET['all'] == 1) {
    // Delete all candidates
    $conn->query("DELETE FROM student_candidates");
    
    // Set success message
    $_SESSION['message'] = "All candidates deleted successfully.";
    $_SESSION['message_type'] = "success";
} else {
    // Invalid request
    $_SESSION['message'] = "Invalid deletion request.";
    $_SESSION['message_type'] = "error";
}

// Redirect back to candidates page
header("Location: candidates.php");
exit();
?>