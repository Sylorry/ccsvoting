<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Database Configuration
$servername = "127.0.0.1";
$username = "u878574291_ccs1";
$password = "CCSPseudocode01";
$database = "u878574291_ccs";

// Establish Database Connection
$conn = mysqli_connect($servername, $username, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if student_id is set
if (isset($_POST['student_id'])) {
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);

    // Restore the student record (you may need to adjust the SQL based on your database schema)
    $sql = "INSERT INTO students (student_id, last_name, first_name, middle_initial, suffix, program, year_level)
            SELECT student_id, last_name, first_name, middle_initial, suffix, program, year_level
            FROM archived_students
            WHERE student_id = '$student_id'";

    if (mysqli_query($conn, $sql)) {
        // Delete from archived_students after restoring
        $delete_sql = "DELETE FROM archived_students WHERE student_id = '$student_id'";
        mysqli_query($conn, $delete_sql);
        $_SESSION['message'] = "Student restored successfully.";
    } else {
        $_SESSION['message'] = "Error restoring student: " . mysqli_error($conn);
    }
}

// Close the database connection
mysqli_close($conn);

// Redirect back to the archived students page
header("Location: archived_students.php");
exit();
?>