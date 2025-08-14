<?php
// Database connection
$servername = "localhost"; // or 127.0.0.1
$username = "root";        // default XAMPP username
$password = "";            // default XAMPP password is empty
$database = "voting";         // make sure this DB exists in phpMyAdmin

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

            // Check if student is already a candidate
            $check_candidate_query = "SELECT student_id FROM student_candidates WHERE student_id = ?";
            $stmt_candidate_check = $conn->prepare($check_candidate_query);
            $stmt_candidate_check->bind_param("i", $student_id);
            $stmt_candidate_check->execute();
            $stmt_candidate_check->store_result();

            if ($stmt_candidate_check->num_rows > 0) {
                // Student is already a candidate
                $error_message = "Student ID $student_id is already registered as a candidate!";
                $stmt_candidate_check->close();
            } else {
                // Student is not yet a candidate, proceed with insertion
                $stmt_candidate_check->close();

                // Insert candidate
                $insert_query = "INSERT INTO student_candidates (student_id, candidate_name, program, year_level, position, party, vote_tally) 
                                 VALUES (?, ?, ?, ?, ?, ?, ?)";

                $stmt = $conn->prepare($insert_query);
                $vote_tally = 0; // Define before binding
                $stmt->bind_param("isssssi", $student_id, $candidate_name, $program, $year_level, $position, $party, $vote_tally);

                if ($stmt->execute()) {
                    $success_message = "Candidate added successfully!";
                } else {
                    // Check for specific duplicate key error
                    if (strpos($stmt->error, 'Duplicate entry') !== false) {
                        $error_message = "Student ID $student_id is already registered as a candidate!";
                    } else {
                        $error_message = "Error adding candidate: " . $stmt->error;
                    }
                }
                $stmt->close();
            }
        } else {
            $error_message = "Student ID does not exist in the students table.";
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
            transition: background-color 0.3s, color 0.3s;
            padding: 10px;
            border-radius: 5px;
        }

        .back-arrow:hover {
            background-color: rgba(255, 255, 255, 0.2);
            color: #ffcc00;
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
            padding: 0 10px;
        }

        label {
            font-weight: bold;
        }

        input, select {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin: 0 auto;
        }

        input[readonly] {
            background-color: #f5f5f5;
            cursor: not-allowed;
        }

        .loading {
            display: none;
            color: #666;
            font-size: 12px;
            margin-top: 5px;
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
            width: calc(100% - 20px);
            margin-top: 15px;
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        // Function to handle student ID input
        $('#student_id').on('input', function() {
            var studentId = $(this).val();
            
            // Clear fields if ID is empty
            if (studentId === '') {
                $('#candidate_name').val('').prop('readonly', false);
                $('#program').val('').prop('readonly', false);
                $('#year_level').val('').prop('readonly', false);
                return;
            }
            
            // Only search if ID has at least 3 digits
            if (studentId.length >= 3) {
                $.ajax({
                    url: 'get_student_details.php',
                    type: 'GET',
                    data: {student_id: studentId},
                    dataType: 'json',
                    success: function(response) {
                        if (response.success && response.data) {
                            // Auto-populate fields
                            $('#candidate_name').val(response.data.full_name).prop('readonly', true);
                            $('#program').val(response.data.program).prop('readonly', true);
                            $('#year_level').val(response.data.year_level).prop('readonly', true);
                        } else {
                            // Clear fields if student not found
                            $('#candidate_name').val('').prop('readonly', false);
                            $('#program').val('').prop('readonly', false);
                            $('#year_level').val('').prop('readonly', false);
                        }
                    },
                    error: function() {
                        console.log('Error fetching student details');
                    }
                });
            }
        });
        
        // Allow manual editing if needed
        $('#candidate_name, #program, #year_level').on('focus', function() {
            if ($(this).prop('readonly')) {
                $(this).prop('readonly', false);
            }
        });
    });
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
                <input type="text" name="student_id" id="student_id" required>
            </div>

            <div class="form-group">
                <label for="candidate_name">Candidate Name:</label>
                <input type="text" name="candidate_name" id="candidate_name" required oninput="toUpperCase(this)">
            </div>

            <div class="form-group">
                <label for="program">Program:</label>
                <input type="text" name="program" id="program" required>
            </div>

            <div class="form-group">
                <label for="year_level">Year Level:</label>
                <input type="text" name="year_level" id="year_level" required>
            </div>

            <div class="form-group">
                <label for="position">Position:</label>
                <select name="position" required>
                    <option value="">Select Position</option>
                    <option value="PRESIDENT">PRESIDENT</option>
                    <option value="INTERNAL VICE-PRESIDENT">INTERNAL VICE-PRESIDENT</option>
                    <option value="EXTERNAL VICE-PRESIDENT">EXTERNAL VICE-PRESIDENT</option>
                    <option value="SECRETARY">SECRETARY</option>
                    <option value="TREASURER">TREASURER</option>
                    <option value="AUDITOR">AUDITOR</option>
                    <option value="PUBLIC INFORMATION OFFICER">PUBLIC INFORMATION OFFICER</option>
                </select>
            </div>

            <div class="form-group">
                <label for="party">Party:</label>
                <input type="text" name="party" required oninput="toUpperCase(this)">
            </div>

            <button type="submit" class="btn">Add Candidate</button>
        </form>
    </div>
</body>
</html>
