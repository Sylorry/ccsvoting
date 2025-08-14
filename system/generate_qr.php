<?php
include 'phpqrcode/qrlib.php'; // Include the QR code library

// Database connection
$servername = "127.0.0.1";
$username = "u878574291_ccs1";
$password = "CCSPseudocode01";
$database = "u878574291_ccs";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Folder to store generated QR codes
$qrDir = "uploads/qrcodes/";
if (!file_exists($qrDir)) {
    mkdir($qrDir, 0777, true);
}

// Fetch students from the database
$sql = "SELECT student_id FROM students";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $studentID = $row['student_id'];
        $qrFileName = $qrDir . $studentID . ".png"; // Use Student ID as filename

        // Generate QR code with Student ID as data
        QRcode::png($studentID, $qrFileName, QR_ECLEVEL_L, 5);

        // Save QR code path in the database
        $updateSql = "UPDATE students SET qr_path = '$qrFileName' WHERE student_id = '$studentID'";
        mysqli_query($conn, $updateSql);
    }
    echo "QR Codes generated and stored successfully!";
} else {
    echo "No students found in the database.";
}

mysqli_close($conn);
?>
