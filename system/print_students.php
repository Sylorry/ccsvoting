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

// Get filter values from the URL
$program = $_GET['program'] ?? null;
$year_level = $_GET['year_level'] ?? null;
$search = $_GET['search'] ?? null;

// Function to filter student records by criteria
function filterStudents($conn, $program = null, $year_level = null, $search = null) {
    $sql = "SELECT student_id, last_name, first_name, middle_initial, suffix, program, year_level 
            FROM students WHERE 1=1";
    $params = [];
    $types = '';

    // Apply filters if provided
    if ($program) {
        $sql .= " AND program = ?";
        $params[] = $program;
        $types .= 's';
    }
    if ($year_level) {
        $sql .= " AND year_level = ?";
        $params[] = $year_level;
        $types .= 's';
    }
    if ($search) {
        $sql .= " AND (last_name LIKE ? OR first_name LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
        $types .= 'ss';
    }
    $sql .= " ORDER BY last_name ASC";

    // Execute Query
    $stmt = mysqli_prepare($conn, $sql);
    if ($params) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}

// Get the filtered students
$filtered_students = filterStudents($conn, $program, $year_level, $search);

// Count total number of students
$total_students = mysqli_num_rows($filtered_students);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Students</title>
    <style>
        /* General body styles */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 20px;
        }

        /* Page layout for printing */
        @page {
            size: A4;
            margin: 20mm;
        }

        /* Header section */
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-align: center;
            margin-bottom: 10px;
        }

        .header img {
            width: 100px; /* Adjust size as needed */
            height: auto;
        }

        h3, h1, h2 {
            text-align: center;
            margin: 0;
        }

        h3 {
            color: #006e2e;
            margin-bottom: 5px;
        }

        p.address {
            text-align: center;
            font-size: 14px;
            color: #333;
            margin-top: 0;
            margin-bottom: 10px;
        }

        h1 {
            color: #006e2e;
        }

        h2 {
            color: #4a4a4a;
        }

        /* Ensure the watermark is on all printed pages */
       /* Ensure the watermark is on all printed pages */
@media print {
    body::after {
        content: "";
        background: url('ccs1.png') no-repeat center;
        background-size: 400px; /* Adjust size as needed */
        opacity: 0.5; /* Increased visibility */
        position: fixed;
        top: 50%;
        left: 50%;
        width: 100%;
        height: 100%;
        transform: translate(-50%, -50%);
        z-index: -1;
    }
}

        }

        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            position: relative;
            background: none; /* Remove inline background */
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            font-size: 18px;
            position: relative;
            background: rgba(255, 255, 255, 0.9); /* Ensure text remains readable */
        }

        th {
            background-color: #006e2e;
            color: white;
        }

        /* Total students count below the table */
        .total-students {
            text-align: center;
            font-size: 18px;
            margin-top: 20px;
            font-weight: bold;
        }

       /* Remove watermark from table area */
table::before {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    width: 300px;
    height: 300px;
    background: url('ccs1.png') no-repeat center;
    background-size: contain;
    opacity: 0.5;
    transform: translate(-50%, -50%);
    z-index: -1;
}

    </style>
</head>
<body>

    <div class="header">
        <img src="dlsjbc.jpg" alt="DLSJBC Logo">
        <div>
            <h3>DE LA SALLE JOHN BOSCO COLLEGE</h3>
            <p class="address">La Salle Drive John Bosco District</p>
            <h1>Students' Profile</h1>
            <h2>CSS PSEUDOCODE.COM SOCIETY</h2>
        </div>
        <img src="ccs.png" alt="CCS Logo">
    </div>

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
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($filtered_students)): ?>
                <tr>
                    <td><?php echo $row['student_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['middle_initial']); ?></td>
                    <td><?php echo htmlspecialchars($row['suffix']); ?></td>
                    <td><?php echo htmlspecialchars($row['program']); ?></td>
                    <td><?php echo htmlspecialchars($row['year_level']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Display Total Count of Students below the table -->
    <div class="total-students">
        Total Students: <?php echo $total_students; ?>
    </div>

    <script>
        window.print(); // Automatically trigger the print dialog
    </script>
</body>
</html>

<?php mysqli_close($conn); ?>
