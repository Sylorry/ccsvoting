<?php
$servername = "localhost"; // or 127.0.0.1
$username = "root";        // default XAMPP username
$password = "";            // default XAMPP password is empty
$database = "voting";         // make sure this DB exists in phpMyAdmin

$conn = new mysqli($servername, $username, $password, $dbname);

$passkey = $_POST['passkey'];
$query = "SELECT * FROM Master_admin WHERE Passkey = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $passkey);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "success";
} else {
    echo "fail";
}
?>
