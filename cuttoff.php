<?php
// Database connection for XAMPP (local development)
$servername = "localhost";
$username = "root";
$password = "";
$database = "voting";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize notification variables
$notification_type = '';
$notification_message = '';

// Fetch existing election data
$result = $conn->query("SELECT * FROM elections LIMIT 1");
$existingElection = $result ? $result->fetch_assoc() : null;

// Handle delete request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {
    if ($conn->query("DELETE FROM elections")) {
        $notification_type = 'success';
        $notification_message = 'Election deleted successfully!';
        $existingElection = null;
    } else {
        $notification_type = 'error';
        $notification_message = 'Error deleting election: ' . $conn->error;
    }
}

// Handle enable/disable toggle
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["toggle_enable"])) {
    if ($existingElection) {
        $newEnable = $existingElection['enable'] == 1 ? 0 : 1;
        if ($conn->query("UPDATE elections SET enable = $newEnable WHERE id = " . $existingElection['id'])) {
            $notification_type = 'success';
            $notification_message = $newEnable == 1 ? "Election has been enabled!" : "Election has been disabled!";
            $existingElection['enable'] = $newEnable;
        } else {
            $notification_type = 'error';
            $notification_message = 'Error updating status: ' . $conn->error;
        }
    }
}

// Handle add or update request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["start_date"])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    if (empty($title) || empty($start_date) || empty($end_date)) {
        $notification_type = 'error';
        $notification_message = 'Title, Start Date, and End Date are required!';
    } else {
        if ($existingElection) {
            $stmt = $conn->prepare("UPDATE elections SET title = ?, description = ?, start_date = ?, end_date = ? WHERE id = ?");
            $successMessage = "Election updated successfully!";
        } else {
            $stmt = $conn->prepare("INSERT INTO elections (title, description, start_date, end_date, enable) VALUES (?, ?, ?, ?, 0)");
            $successMessage = "Election created successfully!";
        }
        if (!$stmt) {
            $notification_type = 'error';
            $notification_message = 'Database error: ' . $conn->error;
        } else {
            if ($existingElection) {
                $stmt->bind_param("ssssi", $title, $description, $start_date, $end_date, $existingElection['id']);
            } else {
                $stmt->bind_param("ssss", $title, $description, $start_date, $end_date);
            }
            if ($stmt->execute()) {
                $notification_type = 'success';
                $notification_message = $successMessage;
                // Refresh election data
                $result = $conn->query("SELECT * FROM elections LIMIT 1");
                $existingElection = $result ? $result->fetch_assoc() : null;
            } else {
                $notification_type = 'error';
                $notification_message = 'Error updating/inserting record: ' . $stmt->error;
            }
            $stmt->close();
        }
    }
}

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
                    <div class="info-label">Title:</div>
                    <div class="info-value">
                        <i class="fas fa-heading"></i> <?= htmlspecialchars($existingElection['title']) ?>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Description:</div>
                    <div class="info-value">
                        <i class="fas fa-info-circle"></i> <?= htmlspecialchars($existingElection['description']) ?>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Start Date:</div>
                    <div class="info-value">
                        <i class="far fa-calendar"></i> <?= htmlspecialchars($existingElection['start_date']) ?>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">End Date:</div>
                    <div class="info-value">
                        <i class="far fa-calendar"></i> <?= htmlspecialchars($existingElection['end_date']) ?>
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
                            <label class="input-label">Title</label>
                            <input type="text" name="title" value="<?= htmlspecialchars($existingElection['title']) ?>" class="form-control" required>
                        </div>
                        
                        <div class="input-group">
                            <label class="input-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($existingElection['description']) ?></textarea>
                        </div>
                        
                        <div class="input-group">
                            <label class="input-label">Start Date</label>
                            <input type="date" name="start_date" value="<?= htmlspecialchars($existingElection['start_date']) ?>" class="form-control" required>
                        </div>
                        
                        <div class="input-group">
                            <label class="input-label">End Date</label>
                            <input type="date" name="end_date" value="<?= htmlspecialchars($existingElection['end_date']) ?>" class="form-control" required>
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
                        <label class="input-label">Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    
                    <div class="input-group">
                        <label class="input-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    
                    <div class="input-group">
                        <label class="input-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>
                    
                    <div class="input-group">
                        <label class="input-label">End Date</label>
                        <input type="date" name="end_date" class="form-control" required>
                    </div>
                    
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-plus-circle"></i> Create Election
                    </button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Notification (hidden by default, shown only if needed) -->
<?php if ($notification_type && $notification_message): ?>
    <div id="notification-<?= $notification_type ?>" class="notification notification-<?= $notification_type ?> show">
        <i class="fas fa-<?= $notification_type == 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
        <span><?= htmlspecialchars($notification_message) ?></span>
    </div>
<?php endif; ?>

<script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete this election? This action cannot be undone.");
    }
    // Hide notification after 5 seconds
    setTimeout(function() {
        var notif = document.querySelector('.notification.show');
        if (notif) notif.classList.remove('show');
    }, 5000);
</script>

</body>
</html>