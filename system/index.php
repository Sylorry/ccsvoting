<?php
session_start(); // Start the session


// Database Configuration
$servername = "127.0.0.1";
$username = "u878574291_ccs1";
$password = "CCSPseudocode01";
$database = "u878574291_ccs";

// Check if the user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}




// Establish Database Connection
$conn = mysqli_connect($servername, $username, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to insert archived student
function insertArchivedStudent($conn, $student_id) {
    $sql = "INSERT INTO archived_students (student_id, last_name, first_name, middle_initial, suffix, program, year_level) 
            SELECT student_id, last_name, first_name, middle_initial, suffix, program, year_level 
            FROM students WHERE student_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $student_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// Handle the archive request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['archive_student_id'])) {
    $student_id = $_POST['archive_student_id'];
    
    // Insert the student into archived_students
    insertArchivedStudent($conn, $student_id);
    
    // Now delete the student from the students table
    $archive_sql = "DELETE FROM students WHERE student_id = ?";
    $stmt = mysqli_prepare($conn, $archive_sql);
    mysqli_stmt_bind_param($stmt, 'i', $student_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    header("Location: " . $_SERVER['PHP_SELF']); // Redirect back to the same page
    exit();
}

// Function to insert dropped student
function insertDroppedStudent($conn, $student_id) {
    $sql = "INSERT INTO dropped_students (student_id, last_name, first_name, middle_initial, suffix, program, year_level) 
            SELECT student_id, last_name, first_name, middle_initial, suffix, program, year_level 
            FROM students WHERE student_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $student_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// Handle the drop request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_id'])) {
    $student_id = $_POST['student_id'];
    
    // Insert the student into dropped_students
    insertDroppedStudent($conn, $student_id);
    
    // Now delete the student from the students table
    $drop_sql = "DELETE FROM students WHERE student_id = ?";
    $stmt = mysqli_prepare($conn, $drop_sql);
    mysqli_stmt_bind_param($stmt, 'i', $student_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    header("Location: " . $_SERVER['PHP_SELF']); // Redirect back to the same page
    exit();
}

// Function to get dropped students
function getDroppedStudents($conn) {
    $sql = "SELECT student_id, last_name, first_name, middle_initial, suffix, program, year_level FROM dropped_students"; 
    return mysqli_query($conn, $sql);
}

// Get the dropped students
$dropped_students = getDroppedStudents($conn);

// Function to count total students or by specific filter
function countStudents($conn, $program = null, $year_level = null, $search = null) {
    $sql = "SELECT COUNT(*) AS total FROM students WHERE 1=1";
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
        $sql .= " AND (last_name LIKE ? OR first_name LIKE ? OR student_id LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
        $params[] = "%$search%"; // Add this line to search by student_id
        $types .= 'sss'; // Update the types to include the new parameter
    }

    // Execute Query
    $stmt = mysqli_prepare($conn, $sql);
    if ($params) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    return (int) $row['total'];
}

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
        $sql .= " AND (last_name LIKE ? OR first_name LIKE ? OR student_id LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
        $params[] = "%$search%"; // Add this line to search by student_id
        $types .= 'sss'; // Update the types to include the new parameter
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

// Get the filtered student count
$program = $_GET['program'] ?? null;
$year_level = $_GET['year_level'] ?? null;
$search = $_GET['search'] ?? null;

// Get the filtered student count
$filtered_count = countStudents($conn, $program, $year_level, $search);

// Default display of all students
$students = filterStudents($conn, $program, $year_level, $search);

// Total student count
$total_students = countStudents($conn);

// Dynamic count by program and year level
$student_counts = [];
$program_year_combinations = mysqli_query($conn, "SELECT DISTINCT program, year_level FROM students ORDER BY program, year_level");
if ($program_year_combinations) {
    while ($row = mysqli_fetch_assoc($program_year_combinations)) {
        $student_counts[$row['program']][$row['year_level']] = countStudents($conn, $row['program'], $row['year_level']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CCS: STUDENTS' PROFILE</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&display=swap" rel="stylesheet">
    <link rel="icon" href="system/ccs3.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        /* General Styles */
        body, html {
            font-family: 'Poppins', sans-serif; /* Ensure Poppins is a clear font */
            margin: 0;
            padding: 0;
            color: #333;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            font-size: 16px; /* Increase base font size */
            line-height: 1.6; /* Increase line height for better readability */
        }

        .header-container h1 {
            text-align: center; /* Center the text */
            font-size: 80px; /* Increase the font size */
            margin: 20px 0; /* Add some margin for spacing */
            text-shadow: none; /* Remove text shadow for clarity */
            font-weight: 600; /* Use a bolder weight for headings */
        }

        .container {
            flex: 1;
            padding: 20px;
            margin-left: 250px; /* Sidebar width */
            transition: margin-left 0.3s;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background-color: #28a745; /* Changed to green */
            color: white;
            position: fixed;
            height: 100%;
            left: -250px; /* Start hidden off-screen */
            transition: left 0.3s ease; /* Smooth transition for the left property */
            z-index: 1000;
        }

        .sidebar.active {
            left: 0; /* Slide in */
        }

        .sidebar a {
            padding: 15px;
            text-decoration: none;
            color: white;
            display: block;
            transition: background 0.3s;
        }

        .sidebar a:hover {
            background: #066403; /* Darker green on hover */
        }

        .toggle-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            background-color: #28a745; /* Changed to green */
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px;
            cursor: pointer;
            z-index: 1100;
        }

        nav {
            background: #28a745; /* Changed to green */
            padding: 10px; /* Reduced padding */
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        nav .logo {
            font-size: 28px; /* Increased font size for the logo */
            display: flex;
            align-items: center;
        }

        nav .logo img {
            height: 40px; /* Adjust the height of the logo image if needed */
            margin-right: 10px; /* Space between image and text */
        }

        .total-students {
            font-size: 24px; /* Increase font size */
            font-weight: bold; /* Ensure it's bold for emphasis */
            margin-bottom: 20px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px; /* Ensure button font size is clear */
            cursor: pointer;
            background-color: #28a745; /* Changed to green */
            color: white;
            margin-left: 10px;
            transition: background 0.3s;
            font-weight: 600; /* Use a bolder weight for buttons */
        }

        .btn:hover {
            background-color: #218838; /* Darker green on hover */
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
            padding: 15px;
            border: 1px solid #ddd;
            font-size: 16px; /* Ensure table font size is clear */
            line-height: 1.5; /* Increase line height for table cells */
            font-family: 'Times New Roman', Times, serif; /* Set font to Times New Roman */
        }
        
        th {
            background-color: #28a745; /* Changed to green */
            color: white;
            text-align: center;
            font-weight: bold; /* Ensure table headers are bold */
        }

        tr:hover {
            background-color: #f1f1f1; /* Light hover effect for clarity */
        }

        /* Zebra Striping */
        tbody tr:nth-child(odd) {
            background-color: #f9f9f9; /* Light background for odd rows */
        }

        /* Input Fields */
        input[type="text"], select {
            padding: 10px; /* Increase padding for input fields */
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px; /* Ensure input font size is clear */
            line-height: 1.5; /* Increase line height for input fields */
        }

       .back-to-top {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #28a745; /* Changed to green */
            color: white;
            border: none;
            border-radius: 50%;
            padding: 10px; /* Adjust padding for a circular button */
            font-size: 20px; /* Adjust font size for the icon */
            cursor: pointer;
            display: none;
            z-index: 1000;
            text-align: center; /* Center the icon */
        }
        
        .back-to-top:hover {
            background-color: #218838; /* Darker green on hover */
        
        }
        
        .drop-btn {
            background-color: #df402d; /* Red color */
            color: white;
        }
        
        .drop-btn:hover {
            background-color: #c0392b; /* Darker red on hover */
        }
        
        .details-btn {
            background-color: #405DE6; /* Blue color */
            color: white;
        }
        
        .details-btn:hover {
            background-color: #0033cc; /* Darker blue on hover */
        }    
            
    

        /* Floating Buttons */
        .floating-trash, .floating-announcement {
            position: fixed;
            background-color: #df402d; /* Keep red for trash */
            color: white;
            border: none;
            border-radius: 50%;
            padding: 15px;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        .floating-trash {
            left: 20px;
            bottom: 20px;
        }

        .floating-announcement {
            display: inline-block;
            padding: 10px 20px;
            transition: all 0.3s;
            font-weight: bold;
            text-align: center;
            text-decoration: none;
            border: 2px solid #000000;
            border-radius: 10px;
            box-shadow: 5px 5px 5px #000000;
            right: 20px;
            top: 20px;
            background-color: #405DE6; /* Changed to blue */
        }

        .floating-announcement:hover {
            background-color: #218838; /* Darker green on hover */
            box-shadow: 5px 5px 5px 0px #218838;
            border: 2px solid black;
            color: white;
        }

        .floating-announcement:active {
            background-color: #098e5f;
            box-shadow: none;
            transform: translateY(4px);
        }

        @media (max-width: 800px) {
            .filter-container {
                flex-direction: column;
            }
            .filter-container select, .filter-container input {
                margin-bottom: 10px;
            }
        }

        @media print {
            .btn, .toggle-btn, .sidebar, .back-to-top, .floating-trash, .floating-announcement {
                display: none; /* Hide buttons and sidebar during print */
            }
        }
    </style>
</head>
<body>
    <button class="toggle-btn" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <div class="sidebar" id="sidebar">
        <h2 style="text-align: center;">Navigation</h2>
        <a href="index.php"><i class="fas fa-home"></i> Home</a>
        <a href="add_student.php"><i class="fas fa-user-plus"></i> Add Student</a>
        <a href="import_students.php"><i class="fas fa-file-import"></i> Import Students</a>
        <a href="archived_students.php"><i class="fas fa-archive"></i> View Archived Students</a>
        <a href="analytics.php"><i class="fas fa-chart-bar"></i> View Summary</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="container">
        <nav>
            <div class="logo">
                <a href="index.php" style="display: flex; align-items: center; text-decoration: none; color: white;">
                    <img src="system/ccs.png" alt="PSEUDOCODE.COM SOCIETY Logo" style="height: 100px;"> <!-- Adjust height if needed -->
                    <span style="font-size: 50px;">PSEUDOCODE.COM SOCIETY</span> <!-- Increased font size -->
                </a>
            </div>
        </nav>

        <div class="header-container">
            <h1>STUDENTS' PROFILE</h1>
        </div>

        <div class="total-students">
            Total Students: <?php echo $total_students; ?>
        </div>

        <!-- Print Button -->
        <button class="btn" onclick="printTable()" title="Print Students' Profile" style="margin-bottom: 10px;">
            <i class="fas fa-print"></i> Print
        </button>

        <!-- Filter Form -->
        <div class="filter-container">
            <form method="get" action="" style="display: flex; align-items: center;">
                <label for="program" style="margin-right: 10px;">Program:</label>
                <select name="program" id="program" style="margin-right: 20px;">
                    <option value="">All</option>
                    <?php
                    // Populate unique program options
                    $programs = mysqli_query($conn, "SELECT DISTINCT program FROM students ORDER BY program");
                    while ($row = mysqli_fetch_assoc($programs)) {
                        $selected = (isset($_GET['program']) && $_GET['program'] === $row['program']) ? "selected" : "";
                        echo "<option value='" . htmlspecialchars($row['program']) . "' $selected>" . htmlspecialchars($row['program']) . "</option>";
                    }
                    ?>
                </select>

                <label for="year_level" style="margin-right: 10px;">Year Level:</label>
                <select name="year_level" id="year_level" style="margin-right: 20px;">
                    <option value="">All</option>
                    <?php
                    // Populate unique year level options
                    $year_levels = mysqli_query($conn, "SELECT DISTINCT year_level FROM students ORDER BY year_level");
                    while ($row = mysqli_fetch_assoc($year_levels)) {
                        $selected = (isset($_GET['year_level']) && $_GET['year_level'] === $row['year_level']) ? "selected" : "";
                        echo "<option value='" . htmlspecialchars($row['year_level']) . "' $selected>" . htmlspecialchars($row['year_level']) . "</option>";
                    }
                    ?>
                </select>
                <button type="submit" class="btn" title="Filter students">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </form>
            <div class="search-container" style="display: flex; align-items: center;">
                <form method="get" action="" style="display: flex; align-items: center; position: relative;">
                    <div style="position: relative; display: flex; align-items: center;">
                        <i class="fas fa-search" style="position: absolute; left: 12px; color: #888;"></i>
                        <input type="text" name="search" placeholder="Search by name or ID..." 
                            value="<?php echo htmlspecialchars($search); ?>" 
                            style="padding-left: 35px; margin-right: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px;">
                    </div>
                    <button type="submit" class="btn" title="Search for students">
                        <i class="fas fa-search"></i> Search
                    </button>
                </form>
            </div>
        </div>

        <!-- Filter Count -->
        <div class="filter-count">
            Filtered Students Count: <?php echo $filtered_count; ?>
        </div>

        <!-- Students Table -->
        <table id="studentsTable">
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
                <?php
                // Apply filters and display students
                $filtered_students = filterStudents($conn, $program, $year_level, $search);
                while ($row = mysqli_fetch_assoc($filtered_students)): ?>
                    <tr>
                        <td><?php echo $row['student_id']; ?></td>
                        <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['middle_initial']); ?></td>
                        <td><?php echo htmlspecialchars($row['suffix']); ?></td>
                        <td><?php echo htmlspecialchars($row['program']); ?></td>
                        <td><?php echo htmlspecialchars($row['year_level']); ?></td>
                        
                        <td>
                            <div style="display: flex; gap: 5px; justify-content: center;">
                                <a href="details.php?id=<?php echo $row['student_id']; ?>" class="btn details-btn" title="View student details">
                                    <i class="fas fa-info-circle"></i> Details
                                </a>
                                <a href="edit_details.php?id=<?php echo $row['student_id']; ?>" class="btn" title="Edit student information">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form method="post" style="display:inline;" onsubmit="return confirmDrop();">
                                    <input type="hidden" name="student_id" value="<?php echo $row['student_id']; ?>">
                                    <button type="submit" class="btn drop-btn" title="Drop this student">
                                        <i class="fas fa-trash"></i> Drop
                                    </button>
                                </form>
                                <?php if ($row['year_level'] === '4TH YEAR'): // Check if the student is in 4TH YEAR ?>
                                    <form method="post" style="display:inline;" onsubmit="return confirmArchive();">
                                        <input type="hidden" name="archive_student_id" value="<?php echo $row['student_id']; ?>">
                                        <button type="submit" class="btn archive-btn" title="Archive this student">
                                            <i class="fas fa-archive"></i> Archive
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Dropped Students Table -->
        <div id="droppedStudentsTable" style="display:none; margin-top: 20px;">
            <h2>Dropped Students</h2>
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
                    <?php while ($dropped_row = mysqli_fetch_assoc($dropped_students)): ?>
                        <tr>
                            <td><?php echo $dropped_row['student_id']; ?></td>
                            <td><?php echo htmlspecialchars($dropped_row['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($dropped_row['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($dropped_row['middle_initial']); ?></td>
                            <td><?php echo htmlspecialchars($dropped_row['suffix']); ?></td>
                            <td><?php echo htmlspecialchars($dropped_row['program']); ?></td>
                            <td><?php echo htmlspecialchars($dropped_row['year_level']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <button class="back-to-top" onclick="scrollToTop()">
            <i class="fas fa-arrow-up"></i> <!-- Use the arrow-up icon -->
        </button>

        <!-- Floating Trash button with location link to restore1.php -->
        <button class="floating-trash" onclick="window.location.href='restore1.php'" title="Restore dropped students">
            <i class="fas fa-trash"></i>
        </button>

        <!-- Floating Announcement button -->
        <button class="floating-announcement" onclick="window.location.href='https://portalview.ccspseudocode.com/announcement.php'" title="View announcements">
            <i class="fas fa-bullhorn"></i>
        </button>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
            const container = document.querySelector('.container');
            container.style.marginLeft = sidebar.classList.contains('active') ? '250px' : '0';
        }
        
        function confirmArchive() {
            return confirm("Are you sure you want to archive this student?");
        }
        
        function confirmDrop() {
            return confirm("Are you sure you want to drop this student?");
        }

        function scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        window.onscroll = function() {
            const button = document.querySelector('.back-to-top');
            if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
                button.style.display = "block";
            } else {
                button.style.display = "none";
            }
        };

                        function printTable() {
                // Get the original table
                const originalTable = document.getElementById('studentsTable');
                
                // Create a new print content with headers
                let printContent = '<div style="text-align: center;">' +
                    '<h1 style="color: #005600; font-size: 24px;">DE LA SALLE JOHN BOSCO COLLEGE</h1>' + // Dark green color and smaller font size
                    '<h2 style="color: #005600; font-size: 20px;">College of Computer Studies</h2>' + // Dark green color and smaller font size
                    '</div>' +
                    '<table style="width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 12px;">' + // Set font size to 12px
                    '<thead><tr>' +
                    '<th style="padding: 8px;">Student ID</th>' + // Adjust padding
                    '<th style="padding: 8px;">Last Name</th>' +
                    '<th style="padding: 8px;">First Name</th>' +
                    '<th style="padding: 8px;">Middle Initial</th>' +
                    '<th style="padding: 8px;">Suffix</th>' +
                    '<th style="padding: 8px;">Program</th>' +
                    '<th style="padding: 8px;">Year Level</th>' +
                    '</tr></thead><tbody>';
                
                // Loop through the rows of the original table and add them to the new table
                const rows = originalTable.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
                for (let row of rows) {
                    const cells = row.getElementsByTagName('td');
                    printContent += '<tr>';
                    for (let i = 0; i < cells.length - 1; i++) { // Exclude the last cell (Action)
                        printContent += '<td style="padding: 8px;">' + cells[i].innerHTML + '</td>'; // Adjust padding
                    }
                    printContent += '</tr>';
                }
                
                // Check if there are fewer than 20 students and add empty rows if necessary
                const totalRows = rows.length;
                const minStudents = 20;
                const emptyRowsNeeded = minStudents - totalRows;
                
                for (let i = 0; i < emptyRowsNeeded; i++) {
                    printContent += '<tr><td colspan="7" style="height: 30px;"></td></tr>'; // Add empty row
                }
                
                printContent += '</tbody></table>';
                
                // Store the original content
                const originalContent = document.body.innerHTML;
                
                // Replace the body content with the print content
                document.body.innerHTML = printContent;
                
                // Open the print dialog
                window.print();
                
                // Restore the original content after printing
                document.body.innerHTML = originalContent;
            }

        // Event listeners for user activity
        document.addEventListener('mousemove', resetSessionTimer);
        document.addEventListener('keypress', resetSessionTimer);
        document.addEventListener('click', resetSessionTimer);
        document.addEventListener('scroll', resetSessionTimer);
    </script>
</body>
</html>

<?php 
mysqli_close($conn); // Close the database connection
?>