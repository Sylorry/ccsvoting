<?php 
ob_start(); // Start output buffering

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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $first_name = strtoupper($_POST['first_name']);
    $last_name = strtoupper($_POST['last_name']);
    $middle_initial = strtoupper($_POST['middle_initial']);
    $suffix = strtoupper($_POST['suffix']);
    $program = $_POST['program'];
    $year_level = $_POST['year_level'];

    // Insert student into the database
    $sql = "INSERT INTO students (student_id, first_name, last_name, middle_initial, suffix, program, year_level) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'issssss', $student_id, $first_name, $last_name, $middle_initial, $suffix, $program, $year_level);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Student added successfully!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Error adding student: " . mysqli_error($conn) . "');</script>";
    }
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #043011; /* Light green background */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: #81c784; /* Darker green background for form container */
            padding: 40px; /* Increased padding for better spacing */
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 1000px; /* Increased width for better layout */
            max-width: 90%; /* Ensure it doesn't exceed 90% of the viewport width */
            border: 2px solid #4caf50; /* Darker green border */
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #000000; /* Black text color for header */
        }
        .input-container {
            position: relative;
            margin: 10px 0;
        }
        .input-container i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #4caf50; /* Darker green for icons */
        }
        .input-container input {
            padding-left: 40px; /* Add padding to avoid overlap with icon */
            width: 100%;
            padding: 15px; /* Increased padding for larger input fields */
            border: 1px solid #4caf50; /* Darker green border */
            border-radius: 5px;
            font-size: 18px; /* Increased font size for better readability */
        }
        select {
            width: 100%;
            padding: 15px; /* Increased padding for larger input fields */
            margin: 10px 0;
            border: 1px solid #4caf50; /* Darker green border */
            border-radius: 5px;
            font-size: 18px; /* Increased font size for better readability */
        }
        .btn {
            padding: 15px 20px; /* Increased button padding */
            border: none;
            border-radius: 5px;
            background-color: #2e7d32; /* Even darker green button */
            color: white;
            cursor: pointer;
            margin-top: 10px;
            width: 100%;
            font-size: 18px; /* Increased button font size */
        }
        .btn:hover {
            background-color: #1b5e20; /* Much darker green on hover */
        }
    </style>
    <script>
        function validateStudentId(input) {
            // Allow only numbers and limit to 7 digits
            input.value = input.value.replace(/[^0-9]/g, '').slice(0, 7);
        }

        function toUpperCase(input) {
            // Convert input to uppercase
            input.value = input.value.toUpperCase();
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Add Student</h2>
        <form method="post" action="">
            <div class="input-container">
                <i class="fas fa-id-card"></i>
                <input type="text" name="student_id" placeholder="Student ID" required oninput="validateStudentId(this)">
            </div>

            <div class="input-container">
                <i class="fas fa-user"></i>
                <input type="text" name="first_name" placeholder="First Name" required oninput="toUpperCase(this)">
            </div>

            <div class="input-container">
                <i class="fas fa-user"></i>
                <input type="text" name="last_name" placeholder="Last Name" required oninput="toUpperCase(this)">
            </div>

            <div class="input-container">
                <i class="fas fa-user-injured"></i>
                <input type="text" name="middle_initial" placeholder="Middle Initial" oninput="toUpperCase(this)">
            </div>

            <div class="input-container">
                <i class="fas fa-user-tag"></i>
                <input type="text" name="suffix" placeholder="Suffix" oninput="toUpperCase(this)">
            </div>

            <select name="program" required>
                <option value="" disabled selected>Select Program</option>
                <option value="CS">CS</option>
                <option value="IT">IT</option>
            </select>

            <select name="year_level" required>
                <option value="" disabled selected>Select Year Level</option>
                <option value="1st Year">1st Year</option>
                <option value="2nd Year">2nd Year</option>
                <option value="3rd Year">3rd Year</option>
                <option value="4th Year">4th Year</option>
            </select>

            <button type="submit" class="btn">Add Student</button>
        </form>
        <button class="btn" onclick="window.location.href='index.php'">Back</button>
    </div>
</body>
</html>

<?php mysqli_close($conn); ?>