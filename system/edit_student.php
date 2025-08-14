<?php
session_start();
$servername = "127.0.0.1";
$username = "u878574291_ccs1";
$password = "CCSPseudocode01";
$dbname = "u878574291_ccs";

// Create a connection
$conn = mysqli_connect($servername, $username, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize variables
$student_id = '';
$last_name = '';
$first_name = '';
$middle_initial = '';
$suffix = '';
$program = '';
$year_level = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    // Get the form data
    $student_id = $_POST['student_id'];
    $last_name = $_POST['last_name'];
    $first_name = $_POST['first_name'];
    $middle_initial = $_POST['middle_initial'];
    $suffix = $_POST['suffix'];
    $program = $_POST['program'];
    $year_level = $_POST['year_level'];
   
    // Prepare the SQL update statement
    $sql = "UPDATE students SET last_name=?, first_name=?, middle_initial=?, suffix=?, program=?, year_level=? WHERE student_id=?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssssss", $last_name, $first_name, $middle_initial, $suffix, $program, $year_level, $student_id);
        
        // Execute the statement
        if ($stmt->execute()) {
            // Redirect to index.php after successful update
            header("Location: index.php?message=Details updated successfully");
            exit(); // Ensure the script stops after redirect
        } else {
            echo "Error updating record: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}

// Fetch student details if the ID is provided in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $student_id = $_GET['id'];
    $sql = "SELECT * FROM students WHERE student_id = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result) {
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $last_name = $row['last_name'];
                $first_name = $row['first_name'];
                $middle_initial = $row['middle_initial'];
                $suffix = $row['suffix'];
                $program = $row['program'];
                $year_level = $row['year_level'];
            } else {
                echo "Student not found!";
                exit;
            }
        } else {
            echo "Error: " . $stmt->error;
            exit;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
        exit;
    }
} else {
    echo "No student ID provided!";
    exit;
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Student</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #73c8a9, #373b44);
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 50%;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            min-height: 600px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        h1 {
            text-align: center;
            color: #4CAF50;
            margin-bottom: 20px;
        }

        label {
            font-size: 16px;
            font-weight: bold;
            margin: 10px 0 5px;
            display: block;
        }

        input[type="text"], input[type="date"], input[type="email"], select {
            width: 100%;
            padding: 10px;
            margin: 10px 0 20px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 14px;
        }

        button[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        .back-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            color: white;
            text-decoration: none;
            background-color: #2ecc71;
            transition: all 0.3s ease;
            display: inline-block;
            margin: 10px 0;
        }

        .back-btn:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>

    <div class="button-container">
        <a href="index.php" class="back-btn">Back</a>
    </div>
    <div class="container">
        <h1>Edit Student Profile</h1>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?id=' . $student_id); ?>" method="post">
            <!-- Student ID field -->
            <label>Student ID:</label>
            <input type="text" name="student_id" value="<?php echo isset($row['student_id']) ? htmlspecialchars($row['student_id']) : ''; ?>" required onkeydown="validateNumericInput(event)" maxlength="7" pattern="\d{7}" title="Student ID must be exactly 7 digits.">

            <label>Last Name:</label>
            <input type="text" name="last_name" value="<?php echo isset($row['last_name']) ? htmlspecialchars($row['last_name']) : ''; ?>" required>

            <label>First Name:</label>
            <input type="text" name="first_name" value="<?php echo isset($row['first_name']) ? htmlspecialchars($row['first_name']) : ''; ?>" required>

            <label>Middle Initial:</label>
            <input type="text" name="middle_initial" value="<?php echo isset($row['middle_initial']) ? htmlspecialchars($row['middle_initial']) : ''; ?>">

            <label>Suffix:</label>
            <input type="text" name="suffix" value="<?php echo isset($row['suffix']) ? htmlspecialchars($row['suffix']) : ''; ?>">

            <label>Program:</label>
            <select name="program" required>
                <option value="IT" <?php echo (isset($row['program']) && $row['program'] == "IT") ? "selected" : ""; ?>>IT</option>
                <option value="CS" <?php echo (isset($row['program']) && $row['program'] == "CS") ? "selected" : ""; ?>>CS</option>
            </select>

            <label>Year Level:</label>
            <select name="year_level" required>
                <option value="1ST YEAR" <?php echo (isset($row['year_level']) && $row['year_level'] == "1ST YEAR") ? "selected" : ""; ?>>1ST YEAR</option>
                <option value="2ND YEAR" <?php echo (isset($row['year_level']) && $row['year_level'] == "2ND YEAR") ? "selected" : ""; ?>>2ND YEAR</option>
                <option value="3RD YEAR" <?php echo (isset($row['year_level']) && $row['year_level'] == "3RD YEAR") ? "selected" : ""; ?>>3RD YEAR</option>
                <option value="4TH YEAR" <?php echo (isset($row['year_level']) && $row['year_level'] == "4TH YEAR") ? "selected" : ""; ?>>4TH YEAR</option>
            </select>

            <button type="submit" name="update">Update</button>
        </form>
    </div>
</body>
</html>
