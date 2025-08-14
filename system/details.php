<?php
// Start output buffering
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

$birth_date = '';
$email_address = '';
$contact_number = '';
$guardian_name = '';
$guardian_contact_number = '';
$profile_picture_url = 'default.jpg';
$home_address = '';
$present_address = '';
$qr_code_url = 'default_qr.jpg'; // Default QR code path

if (isset($_GET['id'])) {
    $student_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($student_id === false || $student_id === 0) {
        echo "<script>alert('Invalid student ID.'); window.location.href='index.php';</script>";
        exit();
    } else {
        $stmt = $conn->prepare("SELECT first_name, middle_initial, last_name, suffix, birth_date, email_address, contact_number, guardian_name, guardian_contact_number, profile_picture, home_address, present_address, qr_path FROM students WHERE student_id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $student_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $full_name = strtoupper(trim($row['first_name'] . ' ' . $row['middle_initial'] . ' ' . $row['last_name'] . ' ' . $row['suffix']));
                $birth_date = $row['birth_date'];
                $email_address = $row['email_address'];
                $contact_number = $row['contact_number'];
                $guardian_name = $row['guardian_name'] ?? 'Not Available';
                $guardian_contact_number = $row['guardian_contact_number'] ?? 'Not Available';
                $profile_picture_url = $row['profile_picture'] ?? 'default.jpg';
                $home_address = $row['home_address'] ?? 'Not Available';
                $present_address = $row['present_address'] ?? 'Not Available';
                $qr_code_url = $row['qr_path'] ?? 'default_qr.jpg'; // Fetch QR code path
            } else {
                echo "<script>alert('Student not found.'); window.location.href='index.php';</script>";
                exit();
            }
            $stmt->close();
        } else {
            die("Prepare statement failed: " . $conn->error);
        }
    }
} else {
    echo "<script>alert('Invalid student ID.'); window.location.href='index.php';</script>";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: #6fec8f;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #333;
        }
        .container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 1500px;
            margin: 20px;
        }
        h1 {
            font-size: 80px;
            color: #1a8001;
            text-align: center;
            margin-bottom: 20px;
        }
        .main-content {
            display: flex;
            gap: 20px;
        }
        .left-section {
            flex: 0 0 300px;
            background: #f8f9fa;
            padding: 20px;
            border-radius:  12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.8);
        }
        .middle-section {
            flex: 1;
            padding: 20px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.8);
        }
        .right-section {
            flex: 0 0 250px;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.8);
        }
        .profile-icon {
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background-size: cover;
            background-position: center;
            border: 4px solid #2ecc71;
            margin-bottom: 20px;
        }
        .name-id-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .name-id-container h2 {
            font-size: 30px;
            margin-bottom: 10px;
            color: #2c3e50;
        }
        .name-id-container p {
            font-size: 20px;
            color: #7f8c8d;
            margin: 0;
        }
        .details-table {
            width: 100%;
            font-size: 30px;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .details-table tr:nth-child(odd) {
            background-color: #f2f2f2;
        }
        .details-table th, .details-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
            font-size: 23px;
        }
        .details-table th {
            background-color: #2ecc71;
            color: white;
        }
        .qr-code {
            width: 300px;
            height: 300px;
            margin-top: 20px;
            border: 2px solid #2ecc71;
            border-radius: 12px;
            background-size: cover;
            background-position: center;
        }
        .button-container {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            padding: 0 20px;
        }
        .button-container a {
            padding: 10px 20px;
            font-size: 20px;
            text-decoration: none;
            border-radius: 8px;
            color: white;
            transition: all 0.3s ease;
        }
        .back-btn {
            background-color: #3498db;
        }
        .edit-btn {
            background-color: #2ecc71;
        }
        .back-btn:hover, .edit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .icon-large {
            font-size: 50px;
            width: 30px;
            color: #2ecc71;
            margin-right: 10px;
        }
        .upload-container {
            margin-top: 20px;
            text-align: center;
        }
        .camera-icon {
            font-size: 50px;
            color: #036828;
            cursor: pointer;
            transition: color 0.3s ease;
        }
        .camera-icon:hover {
            color: #27ae60;
        }
        
        @media (max-width: 1024px) {
            .main-content {
                flex-direction: column;
            }
            .left-section, .right-section {
                width: 100%;
                max-width: 400px;
                margin: 0 auto;
            }
            .middle-section {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Student Details</h1>
        
        <div class="main-content">
            <div class="left-section">
                <div class="profile-icon" style="background-image: url('<?php echo htmlspecialchars($profile_picture_url); ?>');"></div>
                <div class="name-id-container">
                    <h2><?php echo htmlspecialchars($full_name ?? ''); ?></h2>
                    <p>Student ID: <?php echo htmlspecialchars($student_id); ?></p>
                </div>
                <div class="upload-container">
                    <form action="profile_picture.php?id=<?php echo htmlspecialchars($student_id); ?>" method="POST" enctype="multipart/form-data">
                        <i class="fas fa-camera camera-icon" onclick="document.getElementById('file-input').click();"></i>
                        <input type="file" id="file-input" name="profile_picture" accept="image/*" onchange="this.form.submit();" style="display: none;" required>
                    </form>
                </div>
            </div>
            <div class="middle-section">
                <table class="details-table">
                    <tbody>
                        <tr>
                            <th><i class="fas fa-birthday-cake"></i> Birth Date</th>
                            <td><?php echo htmlspecialchars($birth_date); ?></td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-envelope"></i> Email Address</th>
                            <td><?php echo htmlspecialchars($email_address); ?></td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-phone"></i> Contact Number</th>
                            <td><?php echo htmlspecialchars($contact_number); ?></td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-user-friends"></i> Guardian Name</th>
                            <td><?php echo htmlspecialchars($guardian_name); ?></td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-phone-alt"></i> Guardian Contact</th>
                            <td><?php echo htmlspecialchars($guardian_contact_number); ?></td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-home"></i> Home Address</th>
                            <td><?php echo strtoupper(htmlspecialchars($home_address)); ?></td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-map-marker-alt"></i> Present Address</th>
                            <td><?php echo strtoupper(htmlspecialchars($present_address)); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

           <div class="right-section">
                <div class="qr-code" style="background-image: url('<?php echo htmlspecialchars($qr_code_url); ?>');"></div>
                <p>QR Code</p>
                <p>This QR code is exclusively designated for use with the Smart Attendance system of the College of Computer Studies. It must be presented at events. Unauthorized use for non-academic purposes is prohibited, as it contains sensitive information.</p>
            </div>
        </div>

        <div class="button-container">
            <a href="index.php" class="back-btn">Back</a>
            <?php if ($student_id !== 'new'): ?>
                <a href="edit_details.php?id=<?php echo htmlspecialchars($student_id); ?>" class="edit-btn">Edit Details</a>
            <?php endif; ?>
        </div>
    </div>

    <?php mysqli_close($conn); ?>
</body>
</html>