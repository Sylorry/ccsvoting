<?php
// Database connection details
$servername = "127.0.0.1";
$username = "u878574291_ccs1";
$password = "CCSPseudocode01";
$database = "u878574291_ccs";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = filter_input(INPUT_POST, 'student_id', FILTER_VALIDATE_INT);
    $full_name = filter_input(INPUT_POST, 'full_name', FILTER_SANITIZE_STRING);
    $birth_date = filter_input(INPUT_POST, 'birth_date', FILTER_SANITIZE_STRING);
    $email_address = filter_input(INPUT_POST, 'email_address', FILTER_SANITIZE_EMAIL);
    $contact_number = filter_input(INPUT_POST, 'contact_number', FILTER_SANITIZE_STRING);
    $guardian_name = filter_input(INPUT_POST, 'guardian_name', FILTER_SANITIZE_STRING);
    $guardian_contact_number = filter_input(INPUT_POST, 'guardian_contact_number', FILTER_SANITIZE_STRING);
    $home_address = filter_input(INPUT_POST, 'home_address', FILTER_SANITIZE_STRING);
    $present_address = filter_input(INPUT_POST, 'present_address', FILTER_SANITIZE_STRING);

    // Prepare the update statement
    $stmt = $conn->prepare("UPDATE students SET full_name = ?, birth_date = ?, email_address = ?, contact_number = ?, guardian_name = ?, guardian_contact_number = ?, home_address = ?, present_address = ? WHERE student_id = ?");
    if ($stmt) {
        $stmt->bind_param("ssssssssi", $full_name, $birth_date, $email_address, $contact_number, $guardian_name, $guardian_contact_number, $home_address, $present_address, $student_id);
        $stmt->execute();
        $stmt->close();
    } else {
        die("Prepare statement failed: " . $conn->error);
    }

    // Redirect to index.php after updating
    header("Location: index.php");
    exit();
} else {
    echo "<script>alert('Invalid request.'); window.location.href='index.php';</script>";
    exit();
}

$conn->close();
?>