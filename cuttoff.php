<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection parameters
$servername = "127.0.0.1";
$username = "u878574291_ccs1";
$password = "CCSPseudocode01";
$database = "u878574291_ccs";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("<div class='error-message'>Database connection failed: " . $conn->connect_error . "</div>");
}

// Fetch existing election data
$result = $conn->query("SELECT * FROM election LIMIT 1");
$existingElection = $result->fetch_assoc();

// Handle delete request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {
    $conn->query("DELETE FROM election");
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            showNotification('success', 'Election deleted successfully!');
            setTimeout(function() { window.location.href='cuttoff.php'; }, 2000);
        });
    </script>";
}

// Handle enable/disable toggle
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["toggle_enable"])) {
    $newEnable = $existingElection['enable'] == 1 ? 0 : 1;
    $conn->query("UPDATE election SET enable = $newEnable WHERE 1");
    $statusMessage = $newEnable == 1 ? "Election has been enabled!" : "Election has been disabled!";
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            showNotification('success', '$statusMessage');
            setTimeout(function() { window.location.href='cuttoff.php'; }, 2000);
        });
    </script>";
}

// Handle add or update request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["start_time"])) {
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $date = $_POST['date'];

    if (empty($start_time) || empty($end_time) || empty($date)) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                showNotification('error', 'All fields are required!');
            });
        </script>";
    } else {
        if ($existingElection) {
            // Update existing election
            $stmt = $conn->prepare("UPDATE election SET start_time = ?, end_time = ?, date = ? WHERE 1");
            $successMessage = "Election updated successfully!";
        } else {
            // Insert new election
            $stmt = $conn->prepare("INSERT INTO election (start_time, end_time, enable, date) VALUES (?, ?, 0, ?)");
            $successMessage = "Election created successfully!";
        }

        if (!$stmt) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    showNotification('error', 'Database error: " . $conn->error . "');
                });
            </script>";
        } else {
            $stmt->bind_param("sss", $start_time, $end_time, $date);
            
            if ($stmt->execute()) {
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        showNotification('success', '$successMessage');
                        setTimeout(function() { window.location.href='cuttoff.php'; }, 2000);
                    });
                </script>";
            } else {
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        showNotification('error', 'Error updating/inserting record: " . $stmt->error . "');
                    });
                </script>";
            }
            $stmt->close();
        }
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Election Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --danger-color: #e74c3c;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
            --light-color: #ecf0f1;
            --dark-color: #34495e;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
           background: linear-gradient(135deg, #2c3e50, #27ae60);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 900px;
            overflow: hidden;
            position: relative;
        }
        
        .header {
            background: var(--primary-color);
            color: #fff;
            padding: 20px;
            text-align: center;
            border-bottom: 5px solid var(--secondary-color);
        }
        
        .header h2 {
            margin: 0;
            font-size: 28px;
        }
        
        .content {
            padding: 30px;
        }
        
        .back-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            padding: 10px 15px;
            background: var(--secondary-color);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .back-btn:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }
        
        .card {
            background: var(--light-color);
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }
        
        .card h3 {
            color: var(--dark-color);
            border-bottom: 2px solid var(--secondary-color);
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        
        .info-item {
            display: flex;
            margin-bottom: 15px;
            align-items: center;
        }
        
        .info-label {
            font-weight: 600;
            width: 120px;
            color: var(--dark-color);
        }
        
        .info-value {
            flex: 1;
            padding: 8px 12px;
            background: white;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .status-enabled {
            color: var(--success-color);
            font-weight: 600;
        }
        
        .status-disabled {
            color: var(--danger-color);
            font-weight: 600;
        }
        
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
        }
        
        .grid-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .grid-card h3 {
            font-size: 20px;
            color: var(--dark-color);
            margin-bottom: 20px;
            text-align: center;
            border-bottom: 2px solid var(--secondary-color);
            padding-bottom: 10px;
        }
        
        .input-group {
            margin-bottom: 15px;
        }
        
        .input-label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: var(--dark-color);
        }
        
        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: border 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--secondary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }
        
        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
        }
        
        .btn-primary {
            background: var(--secondary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
        }
        
        .btn-danger {
            background: var(--danger-color);
            color: white;
        }
        
        .btn-danger:hover {
            background: #c0392b;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.4);
        }
        
        .btn-warning {
            background: var(--warning-color);
            color: white;
        }
        
        .btn-warning:hover {
            background: #d35400;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(243, 156, 18, 0.4);
        }
        
        .btn-success {
            background: var(--success-color);
            color: white;
        }
        
        .btn-success:hover {
            background: #27ae60;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(46, 204, 113, 0.4);
        }
        
        /* Notification styles */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            z-index: 1001;
            transform: translateX(120%);
            transition: transform 0.4s ease-out;
        }
        
        .notification.show {
            transform: translateX(0);
        }
        
        .notification-success {
            background: var(--success-color);
        }
        
        .notification-error {
            background: var(--danger-color);
        }
        
        @media (max-width: 768px) {
            .grid {
                grid-template-columns: 1fr;
            }
            
            .back-btn {
                top: 10px;
                left: 10px;
                padding: 8px 12px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <a href="admin.php" class="back-btn">
        <i class="fas fa-arrow-left"></i> Back
    </a>
    
    <div class="header">
        <h2><i class="fas fa-calendar-alt"></i> Election Management</h2>
    </div>
    
    <div class="content">
        <?php if ($existingElection): ?>
            <div class="card">
                <h3><i class="fas fa-info-circle"></i> Current Election Details</h3>
                
                <div class="info-item">
                    <div class="info-label">Start Time:</div>
                    <div class="info-value">
                        <i class="far fa-clock"></i> <?= htmlspecialchars($existingElection['start_time']) ?>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">End Time:</div>
                    <div class="info-value">
                        <i class="far fa-clock"></i> <?= htmlspecialchars($existingElection['end_time']) ?>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Date:</div>
                    <div class="info-value">
                        <i class="far fa-calendar"></i> <?= htmlspecialchars($existingElection['date']) ?>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Status:</div>
                    <div class="info-value">
                        <?php if ($existingElection['enable'] == 1): ?>
                            <span class="status-enabled"><i class="fas fa-check-circle"></i> Enabled</span>
                        <?php else: ?>
                            <span class="status-disabled"><i class="fas fa-times-circle"></i> Disabled</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="grid">
                <div class="grid-card">
                    <h3><i class="fas fa-edit"></i> Update Election</h3>
                    <form action="cuttoff.php" method="POST">
                        <div class="input-group">
                            <label class="input-label">Start Time</label>
                            <input type="datetime-local" name="start_time" value="<?= htmlspecialchars($existingElection['start_time']) ?>" class="form-control" required>
                        </div>
                        
                        <div class="input-group">
                            <label class="input-label">End Time</label>
                            <input type="datetime-local" name="end_time" value="<?= htmlspecialchars($existingElection['end_time']) ?>" class="form-control" required>
                        </div>
                        
                        <div class="input-group">
                            <label class="input-label">Date</label>
                            <input type="date" name="date" value="<?= htmlspecialchars($existingElection['date']) ?>" class="form-control" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Election
                        </button>
                    </form>
                </div>
                
                <div class="grid-card">
                    <h3><i class="fas fa-toggle-on"></i> Toggle Status</h3>
                    <p style="margin-bottom: 20px; text-align: center;">
                        Currently <strong><?= $existingElection['enable'] == 1 ? 'Enabled' : 'Disabled' ?></strong>.
                        <?php if ($existingElection['enable'] == 1): ?>
                            Disable to prevent students from voting.
                        <?php else: ?>
                            Enable to allow students to vote.
                        <?php endif; ?>
                    </p>
                    <form action="cuttoff.php" method="POST">
                        <input type="hidden" name="toggle_enable" value="1">
                        <button type="submit" class="btn <?= $existingElection['enable'] == 1 ? 'btn-warning' : 'btn-success' ?>">
                            <?php if ($existingElection['enable'] == 1): ?>
                                <i class="fas fa-ban"></i> Disable Election
                            <?php else: ?>
                                <i class="fas fa-check"></i> Enable Election
                            <?php endif; ?>
                        </button>
                    </form>
                </div>
                
                <div class="grid-card">
                    <h3><i class="fas fa-trash-alt"></i> Delete Election</h3>
                    <p style="margin-bottom: 20px; text-align: center;">
                        Warning: This action cannot be undone. All election settings will be removed.
                    </p>
                    <form action="cuttoff.php" method="POST" onsubmit="return confirmDelete()">
                        <input type="hidden" name="delete" value="1">
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash-alt"></i> Delete Election
                        </button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="grid-card" style="max-width: 500px; margin: 0 auto;">
                <h3><i class="fas fa-plus-circle"></i> Add New Election</h3>
                <form action="cuttoff.php" method="POST">
                    <div class="input-group">
                        <label class="input-label">Start Time</label>
                        <input type="datetime-local" name="start_time" class="form-control" required>
                    </div>
                    
                    <div class="input-group">
                        <label class="input-label">End Time</label>
                        <input type="datetime-local" name="end_time" class="form-control" required>
                    </div>
                    
                    <div class="input-group">
                        <label class="input-label">Date</label>
                        <input type="date" name="date" class="form-control" required>
                    </div>
                    
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-plus-circle"></i> Create Election
                    </button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Success Notification -->
<div id="notification-success" class="notification notification-success">
    <i class="fas fa-check-circle"></i> <span id="success-message">Operation completed successfully!</span>
</div>

<!-- Error Notification -->
<div id="notification-error" class="notification notification-error">
    <i class="fas fa-exclamation-circle"></i> <span id="error-message">An error occurred.</span>
</div>

<script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete this election? This action cannot be undone.");
    }
    
    function showNotification(type, message) {
        const element = document.getElementById('notification-' + type);
        const messageElement = document.getElementById(type + '-message');
        
        if (messageElement) {
            messageElement.textContent = message;
        }
        
        element.classList.add('show');
        
        setTimeout(function() {
            element.classList.remove('show');
        }, 5000);
    }
</script>

</body>
</html>