<?php
session_start();
$servername = "127.0.0.1";
$username = "u878574291_ccs1";
$password = "CCSPseudocode01";
$database = "u878574291_ccs";

// Establish Database Connection
$conn = mysqli_connect($servername, $username, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['query'])) {
    $search = $_GET['query'];
    $sql = "SELECT student_id, last_name, first_name FROM students WHERE last_name LIKE ? OR first_name LIKE ? LIMIT 5";
    $stmt = mysqli_prepare($conn, $sql);
    $like_search = "%$search%";
    mysqli_stmt_bind_param($stmt, 'ss', $like_search, $like_search);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $suggestions = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $suggestions[] = [
            'id' => $row['student_id'],
            'name' => $row['first_name'] . ' ' . $row['last_name']
        ];
    }
    
    echo json_encode($suggestions);
}

mysqli_close($conn);
?>