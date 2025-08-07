<?php
session_start();
require_once 'db.php'; // Ensure this sets up $conn

// Initialize variables to avoid undefined variable warnings
$reset_status = false;
$error_message = '';
$success_message = '';
$total_voters = 0;
$total_voted = 0;
$percentage_voted = 0;

// Process the reset after passkey validation
if (isset($_POST['confirm_reset'])) {
    // Clear votes from the student_candidates table
    if ($conn->query("UPDATE student_candidates SET vote_tally = 0") === TRUE) {
        // Reset the voting status for all students
        if ($conn->query("UPDATE students SET has_voted = 0") === TRUE) {
            $reset_status = true;
            $success_message = "Voting has been reset successfully!";
        } else {
            $error_message = "Error resetting voting status: " . $conn->error;
        }
    } else {
        $error_message = "Error clearing votes: " . $conn->error;
    }
}

// Fetch the total count of student voters
$total_voters_result = $conn->query("SELECT COUNT(*) AS total_voters FROM students");
if ($total_voters_result) {
    $row = $total_voters_result->fetch_assoc();
    $total_voters = $row ? $row['total_voters'] : 0;
}

// Fetch the total count of voters who have completed voting (has_voted = 1)
$total_voted_result = $conn->query("SELECT COUNT(*) AS total_voted FROM students WHERE has_voted = 1");
if ($total_voted_result) {
    $row = $total_voted_result->fetch_assoc();
    $total_voted = $row ? $row['total_voted'] : 0;
}

// Calculate the percentage of students who have voted
$percentage_voted = ($total_voters > 0) ? round(($total_voted / $total_voters) * 100, 2) : 0;

// Close connection
$conn->close();
?>