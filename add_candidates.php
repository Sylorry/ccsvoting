<?php
// Database connection
$servername = "127.0.0.1";
$username = "u878574291_ccs1";
$password = "CCSPseudocode01";
$database = "u878574291_ccs";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize messages
$success_message = "";
$error_message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $candidate_name = mysqli_real_escape_string($conn, strtoupper($_POST['candidate_name']));
    $program = mysqli_real_escape_string($conn, $_POST['program']);
    $year_level = mysqli_real_escape_string($conn, $_POST['year_level']);
    $position = mysqli_real_escape_string($conn, $_POST['position']);
    $party = mysqli_real_escape_string($conn, strtoupper($_POST['party']));

    // Ensure all fields are filled
    if (!empty($student_id) && !empty($candidate_name) && !empty($program) && !empty($year_level) && !empty($position) && !empty($party)) {
        
        // Check if student_id exists in the students table
        $check_query = "SELECT student_id FROM students WHERE student_id = ?";
        $stmt_check = $conn->prepare($check_query);
        $stmt_check->bind_param("i", $student_id);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) { // If student exists
            $stmt_check->close();

            // Insert candidate
            $insert_query = "INSERT INTO student_candidates (student_id, candidate_name, program, year_level, position, party, vote_tally) 
                             VALUES (?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($insert_query);
            $vote_tally = 0; // Define before binding
            $stmt->bind_param("isssssi", $student_id, $candidate_name, $program, $year_level, $position, $party, $vote_tally);

            if ($stmt->execute()) {
                $success_message = "Candidate added successfully!";
            } else {
                $error_message = "Error adding candidate: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $error_message = "Student ID does not exist.";
        }
    } else {
        $error_message = "All fields are required!";
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Candidate</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome CDN -->
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #2c3e50, #27ae60);
            color: white;
            text-align: center;
            padding: 20px;
            position: relative;
        }

        .back-arrow {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 24px;
            text-decoration: none;
            color: white;
            font-weight: bold;
            transition: background-color 0.3s, color 0.3s; /* Add transition for smooth effect */
            padding: 10px; /* Add padding for better click area */
            border-radius: 5px; /* Optional: Add rounded corners */
        }

        .back-arrow:hover {
            background-color: rgba(255, 255, 255, 0.2); /* Change background color on hover */
            color: #ffcc00; /* Change text color on hover */
        }

        .container {
            width: 50%;
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin: auto;
            color: black;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        h2 {
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
            padding: 0 10px; /* Add horizontal padding */
        }

        label {
            font-weight: bold;
        }

        input, select {
            width: calc(100% - 20px); /* Adjust width to account for padding */
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin: 0 auto; /* Center the input/select */
        }

        .btn {
            background-color: #ffcc00;
            color: black;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: bold;
            width: calc(100% - 20px); /* Adjust button width */
            margin-top: 15px; /* Add margin above the button */
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #e6b800;
        }

        .message {
            margin-top: 10px;
            font-weight: bold;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }

        @media (max-width: 768px) {
            .container {
                width: 80%;
            }
        }
    </style>
    <script>
        function toUpperCase(input) {
            input.value = input.value.toUpperCase();
        }
    </script>
</head>
<body>

<a href="vote.php" class="back-arrow"><i class="fas fa-home"></i> HOME</a> <!-- Home icon -->

    <div class="container">
        <h2>Add Candidate</h2>
        <?php if ($success_message): ?>
            <div class="message success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="message error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="student_id">Student ID:</label>
                <input type="text" name="student_id" required>
            </div>

            <div class="form-group">
                <label for="candidate_name">Candidate Name:</label>
                <input type="text" name="candidate_name" required oninput="toUpperCase(this)"> <!-- Convert to uppercase -->
            </div>

            <div class="form-group">
                <label for="program">Program:</label>
                <select name="program" required>
                    <option value="">Select Program</option>
                    <option value="BSIT">BSIT</option>
                    <option value="BSCS">BSCS</option>
                </select>
            </div>

            <div class="form-group">
                <label for="year_level">Year Level:</label>
                <select name="year_level" required>
                    <option value="">Select Year Level</option>
                    <option value="1st Year">1st Year</option>
                    <option value="2nd Year">2nd Year</option>
                    <option value="3rd Year">3rd Year</option>
                    <option value="4th Year">4th Year</option>
                </select>
            </div>

            <div class="form-group">
                <label for="position">Position:</label>
                <select name="position" required>
                    <option value="">Select Position</option>
                    <option value="President">Department President</option>
                    <option value="Vice President">Vice President</option>
                    <option value="Secretary">Secretary</option>
                    <option value="Treasurer">Treasurer</option>
                    <option value="Auditor">Auditor</option>
                    <option value="PIO">PIO</option>
                    <option value="1ST YEAR CLASS MAYOR">1st Year Class Mayor</option>
                    <option value="2ND YEAR CLASS MAYOR">2nd Year Class Mayor</option>
                    <option value="3RD YEAR CLASS MAYOR">3rd Year Class Mayor</option>
                </select>
            </div>

            <div class="form-group">
                <label for="party">Party:</label>
                <input type="text" name="party" required oninput="toUpperCase(this)"> <!-- Convert to uppercase -->
            </div>

            <button type="submit" class="btn">Add Candidate</button>
        </form>
    </div>
</body>
</html>