<?php
// Database connection parameters for XAMPP
$servername = "localhost";      // or "127.0.0.1"
$username = "root";             // default username for XAMPP
$password = "";                 // default password is empty
$database = "voting";              // change this to your local database name in phpMyAdmin

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>