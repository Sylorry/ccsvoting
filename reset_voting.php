<?php
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

// SQL queries to delete votes and reset voters
try {
    // Delete all records from the votes table
    $delete_votes_sql = "DELETE FROM votes";
    $conn->query($delete_votes_sql);

    // Optionally, reset any relevant records in the students table
    // For example, if you have a column to track if a student has voted
    // $reset_students_sql = "UPDATE students SET has_voted = 0";
    // $conn->query($reset_students_sql);

    echo "Voting has been reset successfully.";
} catch (Exception $e) {
    echo "Error resetting voting: " . $e->getMessage();
}

$conn->close();
?>