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

// Function to get archived students and count
function getArchivedStudents($conn) {
    $sql = "SELECT student_id, last_name, first_name, middle_initial, suffix, program, year_level, 
            YEAR(graduation_year) AS graduation_year, 
            DATE_FORMAT(archived_date, '%Y-%m-%d') AS archived_date 
            FROM archived_students"; 
    $result = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($result); // Count the number of rows
    return [$result, $count]; // Return both the result set and the count
}

// Get the archived students and count
list($archived_students, $total_students) = getArchivedStudents($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archived Students</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        /* Add your styles here */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            padding: 20px;
        }
        h1 {
            text-align: center; /* Center the title */
            margin: 0; /* Remove default margin */
        }
        h2 {
            color: #005600; /* Dark green color for h2 */
            margin: 0; /* Remove default margin */
        }
        h3 {
            color: #004400; /* Darker green color for h3 */
            margin: 0; /* Remove default margin */
        }
        .print-header {
            display: none; /* Hide by default */
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            font-family: 'Times New Roman', Times, serif; /* Set font to Times New Roman */
        }
        th, td {
            padding: 5px; /* Further reduced padding for smaller height */
            border: 1px solid #ddd;
            text-align: center;
            font-family: 'Times New Roman', Times, serif; /* Set font to Times New Roman */
            height: 25px; /* Optional: Set a smaller fixed height for rows */
        }
        th {
            background-color: #28a745; /* Green background color */
            color: white; /* White text color for contrast */
        }
        tbody tr:nth-child(odd) {
            background-color: #f9f9f9; /* Light gray for odd rows */
        }
        tbody tr:nth-child(even) {
            background-color: #ffffff; /* White for even rows */
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin-bottom: 20px; /* Adjust margin to create space below the button */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            font-size: 16px; /* Increase font size for better visibility */
        }
        .btn.green {
            background-color: #28a745; /* Green color for other buttons */
        }
        .btn.green:hover {
            background-color: #218838; /* Darker shade on hover */
        }
        .btn.restore {
            background-color: #007bff; /* Bootstrap primary color for restore button */
        }
        .btn.restore:hover {
            background-color: #0056b3; /* Darker shade on hover for restore button */
        }
        .btn i {
            margin-right: 5px; /* Space between icon and text */
        }
        @media print {
            .action-cell, /* Hide action cells */
            .action-header, /* Hide action header */
            .print-button, /* Hide print button */
            .back-button { /* Hide back button */
                display: none; 
            }
            .print-header {
                display: block; /* Show print header */
            }
            body {
                margin: 0; /* Remove body margin for print */
                padding: 0; /* Remove body padding for print */
            }
            table {
                margin-top: 20px; /* Add margin to the top of the table */
            }
        }
    </style>
    <script>
        function printPage() {
            window.print();
        }
    </script>
</head>
<body>
    <a href="index.php" class="btn green back-button"><i class="fas fa-arrow-left"></i> Back to Student Records</a>
    <button class="btn green print-button" onclick="printPage()"><i class="fas fa-print"></i> Print</button> <!-- Print Button -->
    
    <div class="print-header">
        <h2>DE LA SALLE JOHN BOSCO COLLEGE</h2>
        <h3>College of Computer Studies</h3>
    </div>

    <h1>Archived Students</h1>
    
    <p style="text-align: center; font-size: 18px; margin: 0;">Total Archived Students: <strong><?php echo $total_students; ?></strong></p> <!-- Display total count -->

    <table>
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Last Name</th>
                <th>First Name</th>
                <th>Middle Initial</th>
                <th>Suffix</th>
                <th>Program</th>
                <th>Year Level</th>
                <th>Year Graduated</th>
                <th>Archived Date</th> <!-- New Archived Date column -->
                <th class="action-header">Action</th> <!-- Add class to hide during print -->
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($archived_students)): ?>
                <tr>
                    <td><?php echo $row['student_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['middle_initial']); ?></td>
                    <td><?php echo htmlspecialchars($row['suffix']); ?></td>
                    <td><?php echo htmlspecialchars($row['program']); ?></td>
                    <td><?php echo htmlspecialchars($row['year_level']); ?></td>
                    <td><?php echo htmlspecialchars($row['graduation_year']); ?></td> <!-- Display Year Graduated -->
                    <td><?php echo htmlspecialchars($row['archived_date']); ?></td> <!-- Display Archived Date -->
                    <td class="action-cell"> <!-- Add class to hide during print -->
                        <form action="restore_student.php" method="POST" style="display:inline;">
                            <input type="hidden" name="student_id" value="<?php echo $row['student_id']; ?>">
                            <button type="submit" class="btn restore" onclick="return confirm('Are you sure you want to restore this student?');"><i class="fas fa-undo"></i> Restore</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>

<?php 
mysqli_close($conn); // Close the database connection
?>