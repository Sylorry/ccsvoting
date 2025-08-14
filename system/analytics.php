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

// Function to get all students
function getAllStudents($conn) {
    $sql = "SELECT student_id, program, year_level FROM students ORDER BY year_level, program"; 
    return mysqli_query($conn, $sql);
}

// Get all students
$students = getAllStudents($conn);

// Organize students by year level and program
$organized_students = [];
$total_count = 0; // Initialize total count
while ($row = mysqli_fetch_assoc($students)) {
    $year_level = $row['year_level'];
    $program = $row['program'];
    
    if (!isset($organized_students[$year_level])) {
        $organized_students[$year_level] = [];
    }
    
    if (!isset($organized_students[$year_level][$program])) {
        $organized_students[$year_level][$program] = 0; // Initialize count
    }
    
    $organized_students[$year_level][$program]++; // Increment count
    $total_count++; // Increment total count
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics - Student Records</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        /* Add your styles here */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #28a745; /* Green */
        }
        .header h2 {
            margin: 0;
            font-size: 20px;
            color: #28a745; /* Green */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 15px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #28a745; /* Green */
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        h1 {
            color: #28a745; /* Green */
        }
        .total-row {
            font-weight: bold;
            background-color: #e9ecef; /* Light gray for total row */
        }
        /* Styles for the buttons */
        .back-button, .print-button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #28a745; /* Green */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            display: flex;
            align-items: center; /* Center the icon and text vertically */
        }
        .back-button:hover, .print-button:hover {
            background-color: #218838; /* Darker green on hover */
        }
        .button-icon {
            margin-right: 8px; /* Space between icon and text */
        }
    </style>
    <script>
        function printPage() {
            window.print();
        }
    </script>
</head>
<body>
    <div class="header">
        <h1>DE LA SALLE JOHN BOSCO COLLEGE</h1>
        <h2>COLLEGE OF COMPUTER STUDIES</h2>
    </div>

    <h1>Total Count of Students</h1>

    <table>
        <thead>
            <tr>
                <th>Year Level</th>
                <th>Program</th>
                <th>Total Students</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($organized_students as $year_level => $programs): ?>
                <?php foreach ($programs as $program => $count): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($year_level); ?></td>
                        <td><?php echo htmlspecialchars($program); ?></td>
                        <td><?php echo $count; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
            <tr class="total-row">
                <td colspan="2">Total Students</td>
                <td><?php echo $total_count; ?></td>
            </tr>
        </tbody>
    </table>

    <button class="print-button" onclick="printPage()">
        <i class="fas fa-print button-icon"></i> Print
    </button>
    <button class="back-button" onclick="window.location.href='index.php'">
        <i class="fas fa-arrow-left button-icon"></i> Back to Dashboard
    </button>

</body>
</html>

<?php 
mysqli_close($conn); // Close the database connection
?>