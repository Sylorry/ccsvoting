<?php
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

// Function to restore dropped student
function restoreDroppedStudent($conn, $student_id) {
    // Insert the student back into the students table
    $sql = "INSERT INTO students (student_id, last_name, first_name, middle_initial, suffix, program, year_level) 
            SELECT student_id, last_name, first_name, middle_initial, suffix, program, year_level 
            FROM dropped_students WHERE student_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $student_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Delete the student from the dropped_students table
    $delete_sql = "DELETE FROM dropped_students WHERE student_id = ?";
    $stmt = mysqli_prepare($conn, $delete_sql);
    mysqli_stmt_bind_param($stmt, 'i', $student_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// Handle the restore request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_id'])) {
    $student_id = $_POST['student_id'];

    // Restore the dropped student
    restoreDroppedStudent($conn, $student_id);

    // Redirect back to index.php after restoration
    header("Location: index.php");
    exit();
}

// Function to get dropped students
function getDroppedStudents($conn) {
    $sql = "SELECT student_id, last_name, first_name, middle_initial, suffix, program, year_level 
            FROM dropped_students";
    return mysqli_query($conn, $sql);
}

// Get the dropped students
$dropped_students = getDroppedStudents($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restore Dropped Students</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Your styles for the page */
        body, html {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #006e2e;
            color: white;
        }

        tr:hover {
            background-color: #f2f2f2;
        }

        .btn {
            padding: 10px 20px;
            background-color: #0ad82a;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #1c7fcc;
        }

        .back-btn {
            padding: 10px 20px;
            background-color: #f44336; /* Red color */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none; /* Remove underline */
            display: inline-block; /* Make it inline-block */
            margin-bottom: 20px; /* Add some space below */
        }

        .back-btn:hover {
            background-color: #d32f2f; /* Darker red on hover */
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Restore Dropped Students</h2>
    <a href="index.php" class="back-btn">Back</a> <!-- Back button -->
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
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($dropped_students)): ?>
                <tr>
                    <td><?php echo $row['student_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['middle_initial']); ?></td>
                    <td><?php echo htmlspecialchars($row['suffix']); ?></td>
                    <td><?php echo htmlspecialchars($row['program']); ?></td>
                    <td><?php echo htmlspecialchars($row['year_level']); ?></td>
                    <td>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="student_id" value="<?php echo $row['student_id']; ?>">
                            <button type="submit" class="btn">Restore</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>

<?php mysqli_close($conn); ?>