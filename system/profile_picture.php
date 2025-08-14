<?php
// Database connection details
$servername = "127.0.0.1";
$username = "u878574291_ccs1";
$password = "CCSPseudocode01";
$database = "u878574291_ccs";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Check if the student ID is provided
if (isset($_GET['id'])) {
    $student_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($student_id === false || $student_id === 0) {
        echo "<script>alert('Invalid student ID.'); window.location.href='index.php';</script>";
        exit();
    }

    // Handle file upload
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_picture'])) {
        $target_dir = "uploads/"; // Directory where the file will be uploaded
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
        if ($check === false) {
            echo "<script>alert('File is not an image.');</script>";
            $uploadOk = 0;
        }

        // Check file size (limit to 2MB)
        if ($_FILES["profile_picture"]["size"] > 2000000) {
            echo "<script>alert('Sorry, your file is too large.');</script>";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
            echo "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.');</script>";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk === 0) {
            echo "<script>alert('Sorry, your file was not uploaded.');</script>";
        } else {
            // If everything is ok, try to upload file
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                // Update the database with the new profile picture path
                $stmt = $conn->prepare("UPDATE students SET profile_picture=? WHERE student_id=?");
                if ($stmt) {
                    $stmt->bind_param("si", $target_file, $student_id);
                    if ($stmt->execute()) {
                        echo "<script>alert('Profile picture updated successfully.');</script>";
                    } else {
                        echo "<script>alert('Error updating profile picture in database.');</script>";
                    }
                    $stmt->close();
                } else {
                    die("Prepare statement failed: " . $conn->error);
                }
            } else {
                echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
            }
        }
    }
} else {
    echo "<script>alert('Invalid student ID.'); window.location.href='index.php';</script>";
    exit();
}

mysqli_close($conn);
header("Location: details.php?id=" . $student_id); // Redirect back to the details page
exit();
?>