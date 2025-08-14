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

// Handle CSV file upload
$message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['csv_file'])) {
    $file = $_FILES['csv_file'];

    // Check for errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $message = "File upload error.";
    } else {
        // Open the CSV file
        if (($handle = fopen($file['tmp_name'], 'r')) !== FALSE) {
            fgetcsv($handle); // Skip the header row
            
            $insert_sql = "INSERT INTO students 
            (student_id, last_name, first_name, middle_initial, suffix, program, year_level, birth_date, email_address, contact_number, guardian_name, guardian_contact_number, home_address, present_address) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insert_sql);
            
            while (($data = fgetcsv($handle)) !== FALSE) {
                mysqli_stmt_bind_param($stmt, 'ssssssssssssss', $data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7], $data[8], $data[9], $data[10], $data[11], $data[12], $data[13]);
                mysqli_stmt_execute($stmt);
            }
            
            fclose($handle);
            mysqli_stmt_close($stmt);
            $message = "Students imported successfully!";
        } else {
            $message = "Error opening the file.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Students</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome CDN -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f5f5dc; /* Cream color background for the entire page */
            margin: 0;
        }
        .container {
            background: #fff; /* White background for the form */
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 600px; /* Increased width for better readability */
            min-width: 400px;
        }
        h1 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #388e3c; /* Lighter green for the header */
        }
        input[type="file"] {
            margin: 20px 0;
            padding: 14px;
            border: 1px solid #66bb6a; /* Lighter green border */
            border-radius: 5px;
            width: 100%;
            font-size: 18px; /* Larger font size */
        }
        .btn {
            padding: 14px 28px;
            border: none;
            border-radius: 5px;
            font-size: 18px; /* Larger font size for button */
            cursor: pointer;
            background-color: #66bb6a; /* Lighter green button */
            color: white;
            width: 100%;
            transition: background 0.3s;
        }
        .btn:hover {
            background-color: #57a65a; /* Slightly darker green on hover */
        }
        .message {
            margin-top: 20px;
            padding: 12px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
        }
        .success {
            background-color: #66bb6a; /* Lighter green success message */
            color: white;
            border: 1px solid #4caf50;
        }
        .error {
            background-color: #e74c3c; /* Red error message */
            color: white;
            border: 1px solid #c0392b;
        }
        .back-arrow {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 24px;
            color: #fff;
            text-decoration: none;
            background: #388e3c; /* Dark green background for the back button */
            padding: 12px 16px; /* Adjusted padding for better appearance */
            border-radius: 8px; /* Slightly larger border radius for a softer look */
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Added shadow for depth */
        }
        .back-arrow:hover {
            background: #57a65a; /* Slightly lighter green when hovered */
            transform: scale(1.1); /* Slightly enlarge the button on hover */
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3); /* Darker shadow on hover */
        }
        .back-arrow i {
            margin-right: 8px; /* Space between the icon and text */
        }
    </style>
</head>
<body>
    <a href="index.php" class="back-arrow"><i class="fas fa-arrow-left"></i> Back</a> <!-- Custom back button with icon -->
    <div class="container">
        <h1>Import Students</h1>
        <?php if ($message): ?>
            <div class="message <?php echo strpos($message, 'successfully') !== false ? 'success' : 'error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data">
            <input type="file" name="csv_file" accept=".csv" required>
            <button type="submit" class="btn">Import</button>
        </form>
    </div>
</body>
</html>

<?php mysqli_close($conn); ?>