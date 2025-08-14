<?php
// Start output buffering to avoid header issues
ob_start();

// Database connection details
$servername = "127.0.0.1";
$username = "u878574291_ccs1";
$password = "CCSPseudocode01";
$database = "u878574291_ccs";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Get student ID from URL parameter
$student_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$student_id) {
    echo "<script>alert('Invalid student ID.'); window.location.href='index.php';</script>";
    exit();
}

// Fetch current student details
$stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // Pre-fill form fields with existing student data
    $full_name = $row['full_name'] ?? '';
    $birth_date = $row['birth_date'] ?? '';
    $email_address = $row['email_address'] ?? '';
    $contact_number = $row['contact_number'] ?? '';
    $guardian_name = $row['guardian_name'] ?? '';
    $guardian_contact_number = $row['guardian_contact_number'] ?? '';
    $home_address = $row['home_address'] ?? '';
    $present_address = $row['present_address'] ?? '';
} else {
    echo "<script>alert('Student not found.'); window.location.href='index.php';</script>";
    exit();
}
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $birth_date = trim($_POST['birth_date']);
    $email_address = trim($_POST['email_address']);
    $contact_number = trim($_POST['contact_number']);
    $guardian_name = trim($_POST['guardian_name']);
    $guardian_contact_number = trim($_POST['guardian_contact_number']);
    $home_address = trim($_POST['home_address']);
    $present_address = trim($_POST['present_address']);

    // Update student details in the database
    $update_stmt = $conn->prepare("UPDATE students SET full_name=?, birth_date=?, email_address=?, contact_number=?, guardian_name=?, guardian_contact_number=?, home_address=?, present_address=? WHERE student_id=?");
    $update_stmt->bind_param("ssssssssi", 
        $full_name, 
        $birth_date, 
        $email_address, 
        $contact_number, 
        $guardian_name, 
        $guardian_contact_number, 
        $home_address, 
        $present_address, 
        $student_id
    );

    if ($update_stmt->execute()) {
        // Redirect to details page after successful update
        header("Location: details.php?id=" . $student_id);
        exit();
    } else {
        echo "<script>alert('Error updating record.');</script>";
    }
    $update_stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: #043011; /* Softer green background */
            text-align: center; 
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container { 
            width: 40%; 
            background: #fff; 
            padding: 25px; 
            border-radius: 10px; 
            box-shadow: 0px 4px 15px rgba(0, 64, 0, 0.3); /* Dark green shadow */
            text-align: left;
        }

        h1 { 
            color: #155724; /* Darker green title */
            text-align: center;
        }

        form { 
            display: flex; 
            flex-direction: column; 
        }

        label { 
            margin-top: 12px; 
            font-weight: 600; 
            color: #0b3d02; /* Darkest green for labels */
        }

        input { 
            padding: 10px; 
            margin-top: 5px; 
            border: 1px solid #155724; /* Darker green border */
            border-radius: 6px; 
            text-transform: uppercase; 
            font-size: 14px;
            background-color: #f8f9fa; /* Light background for better contrast */
        }

        input:focus {
            outline: none;
            border-color: #0b3d02; /* Even darker green when focused */
            box-shadow: 0 0 5px rgba(11, 61, 2, 0.5);
        }

        input[type="email"] { 
            text-transform: none !important; 
        }

        button { 
            margin-top: 20px; 
            padding: 12px; 
            background: #0b3d02; /* Darkest green button */
            color: white; 
            border: none; 
            border-radius: 6px; 
            font-size: 16px;
            cursor: pointer; 
            transition: background 0.3s;
        }

        button:hover { 
            background: #064d00; /* Even darker on hover */
        }

        a { 
            text-decoration: none; 
            color: white; /* White text for contrast */
            margin-top: 15px; 
            display: flex; /* Change to flex display */
            justify-content: center; /* Center horizontally */
            align-items: center; /* Center vertically */
            font-weight: 600;
            padding: 12px; /* Add padding to make it look like a button */
            background: #dc3545; /* Bootstrap danger color for cancel */
            border-radius: 6px; /* Rounded corners */
            transition: background 0.3s; /* Smooth transition */
        }

        a:hover { 
            background: #c82333; /* Darker red on hover */
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Edit Student Details</h1>
    <form action="" method="POST">
        <label for="full_name"><i class="fas fa-user"></i> Full Name:</label>
        <input type="text" name="full_name" id="full_name" value="<?php echo htmlspecialchars($full_name); ?>" required>

        <label for="birth_date"><i class="fas fa-calendar-alt"></i> Birth Date:</label>
        <input type="date" name="birth_date" id="birth_date" value="<?php echo htmlspecialchars($birth_date); ?>" required>

        <label for="email_address"><i class="fas fa-envelope"></i> Email Address:</label>
        <input type="email" name="email_address" id="email_address" value="<?php echo htmlspecialchars($email_address); ?>" required>

        <label for="contact_number"><i class="fas fa-phone"></i> Contact Number:</label>
        <input type="text" name="contact_number" id="contact_number" value="<?php echo htmlspecialchars($contact_number); ?>" required pattern="\d{11}" maxlength="11" title="Please enter exactly 11 digits.">

        <label for="guardian_name"><i class="fas fa-user-shield"></i> Guardian Name:</label>
        <input type="text" name="guardian_name" id="guardian_name" value="<?php echo htmlspecialchars($guardian_name); ?>" required>

        <label for="guardian_contact_number"><i class="fas fa-phone-alt"></i> Guardian Contact Number:</label>
        <input type="text" name="guardian_contact_number" id="guardian_contact_number" value="<?php echo htmlspecialchars($guardian_contact_number); ?>" required pattern="\d{11}" maxlength="11" title="Please enter exactly 11 digits.">

        <label for="home_address"><i class="fas fa-home"></i> Home Address:</label>
        <input type="text" name="home_address" id="home_address" value="<?php echo htmlspecialchars($home_address); ?>" required>

        <label for="present_address"><i class="fas fa-map-marker-alt"></i> Present Address:</label>
        <input type="text" name="present_address" id="present_address" value="<?php echo htmlspecialchars($present_address); ?>" required>

        <button type="submit">Update Details</button>
    </form>
    <a href="details.php?id=<?php echo htmlspecialchars($student_id); ?>">Cancel</a>
</div>
</body>
</html>