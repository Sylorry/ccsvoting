<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "voting";

$conn = new mysqli($servername, $username, $password, $database);

// Set JSON header
header('Content-Type: application/json');

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit();
}

$student_id = isset($_GET['student_id']) ? intval($_GET['student_id']) : 0;

if ($student_id > 0) {
    $stmt = $conn->prepare("SELECT student_id, full_name, program, year_level FROM students WHERE student_id = ?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        echo json_encode([
            'success' => true,
            'data' => $row
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Student not found']);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid student ID']);
}

$conn->close();
?>
