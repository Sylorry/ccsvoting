<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "127.0.0.1";
$username = "u878574291_ccs1";
$password = "CCSPseudocode01";
$database = "u878574291_ccs";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$reset_status = false;
$error_message = '';
$success_message = '';

// Process the reset after passkey validation
if (isset($_POST['confirm_reset'])) {
    // Clear votes from the votes table
    if ($conn->query("Update student_candidates SET vote_tally = 0") === TRUE) {
        // Reset the voting status for all students
        if ($conn->query("UPDATE students SET has_voted = 0") === TRUE) {
            $reset_status = true;
            $success_message = "Voting has been reset successfully!";
        } else {
            $error_message = "Error resetting voting status: " . $conn->error;
        }
    } else {
        $error_message = "Error clearing votes: " . $conn->error;
    }
}

// Fetch the total count of student voters
$total_voters_result = $conn->query("SELECT COUNT(*) AS total_voters FROM students");
$total_voters = $total_voters_result->fetch_assoc()['total_voters'];

// Fetch the total count of voters who have completed voting (has_voted = 1)
$total_voted_result = $conn->query("SELECT COUNT(*) AS total_voted FROM students WHERE has_voted = 1");
$total_voted = $total_voted_result->fetch_assoc()['total_voted'];

// Calculate the percentage of students who have voted
$percentage_voted = ($total_voters > 0) ? round(($total_voted / $total_voters) * 100, 2) : 0;

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Election Management</title>
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
            max-width: 800px;
            overflow: hidden;
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
        
        .button-group {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
            margin: 25px 0;
        }
        
        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s ease;
            min-width: 180px;
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
        
        .btn-primary {
            background: var(--secondary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
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
        
        .stats-container {
            background: var(--light-color);
            border-radius: 10px;
            padding: 25px;
            margin-top: 30px;
        }
        
        .stats-title {
            font-size: 20px;
            color: var(--dark-color);
            margin-bottom: 20px;
            text-align: center;
            border-bottom: 2px solid var(--secondary-color);
            padding-bottom: 10px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            text-align: center;
        }
        
        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }
        
        .stat-title {
            font-size: 14px;
            color: #777;
            margin-bottom: 5px;
        }
        
        .stat-value {
            font-size: 28px;
            font-weight: bold;
            color: var(--dark-color);
        }
        
        .progress-container {
            margin-top: 15px;
            padding: 10px;
            border-radius: 8px;
            background: white;
        }
        
        .progress-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        
        .progress-bar {
            height: 10px;
            background: #e0e0e0;
            border-radius: 5px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: var(--secondary-color);
            border-radius: 5px;
        }
        
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(3px);
        }
        
        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 0;
            width: 400px;
            border-radius: 12px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.3);
            animation: modalOpen 0.4s ease-out;
            overflow: hidden;
        }
        
        @keyframes modalOpen {
            from {opacity: 0; transform: translateY(-50px);}
            to {opacity: 1; transform: translateY(0);}
        }
        
        .modal-header {
            background: var(--danger-color);
            color: white;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .modal-title {
            font-size: 20px;
            font-weight: 600;
            margin: 0;
        }
        
        .close {
            font-size: 28px;
            font-weight: 700;
            color: white;
            background: transparent;
            border: none;
            cursor: pointer;
            transition: color 0.2s;
        }
        
        .close:hover {
            color: #eee;
        }
        
        .modal-body {
            padding: 25px;
        }
        
        .modal-footer {
            padding: 15px 25px;
            background: #f8f9fa;
            text-align: right;
            border-top: 1px solid #eee;
        }
        
        #passkey-form input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            margin: 15px 0;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: border 0.3s;
        }
        
        #passkey-form input[type="password"]:focus {
            border-color: var(--secondary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }
        
        #passkey-form button {
            padding: 12px 25px;
            background-color: var(--danger-color);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        #passkey-form button:hover {
            background-color: #c0392b;
        }
        
        .error-message {
            color: var(--danger-color);
            margin: 10px 0;
            font-weight: 500;
        }
        
        .success-message {
            color: var(--success-color);
            margin: 10px 0;
            font-weight: 500;
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
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .button-group {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 100%;
            }
            
            .modal-content {
                width: 90%;
            }
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div class="container">
    <div class="header">
        <h2><i class="fas fa-user-shield"></i> Election Admin Panel</h2>
    </div>
    
    <div class="content">
        <div class="button-group">
            <button id="reset-btn" class="btn btn-danger">
                <i class="fas fa-redo-alt"></i> Reset Voting
            </button>
            <button class="btn btn-primary" onclick="window.location.href='vote.php'">
                <i class="fas fa-home"></i> Home
            </button>
            <button class="btn btn-success" onclick="window.location.href='cuttoff.php'">
                <i class="fas fa-cogs"></i> Manage Election
            </button>
        </div>
        
        <div class="stats-container">
            <div class="stats-title">
                <i class="fas fa-chart-bar"></i> Voting Statistics
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-title">Total Students</div>
                    <div class="stat-value"><?php echo htmlspecialchars($total_voters); ?></div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-title">Votes Cast</div>
                    <div class="stat-value"><?php echo htmlspecialchars($total_voted); ?></div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-title">Completion Rate</div>
                    <div class="stat-value"><?php echo htmlspecialchars($percentage_voted); ?>%</div>
                </div>
            </div>
            
            <div class="progress-container">
                <div class="progress-label">
                    <span>Voting Progress</span>
                    <span><?php echo htmlspecialchars($total_voted); ?> / <?php echo htmlspecialchars($total_voters); ?></span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: <?php echo htmlspecialchars($percentage_voted); ?>%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Passkey Modal -->
<div id="passkey-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title"><i class="fas fa-key"></i> Administrator Verification</h3>
            <button class="close">&times;</button>
        </div>
        <div class="modal-body">
            <p>Please enter your administrator passkey to reset the voting system. This action cannot be undone.</p>
            <form id="passkey-form">
                <input type="password" id="passkey-input" placeholder="Enter passkey" required>
                <p id="passkey-error" class="error-message"></p>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" id="verify-btn">
                <i class="fas fa-check-circle"></i> Verify & Reset
            </button>
        </div>
    </div>
</div>

<!-- Hidden form to submit after passkey validation -->
<form id="confirm-reset-form" method="POST" style="display: none;">
    <input type="hidden" name="confirm_reset" value="1">
</form>

<!-- Success Notification -->
<div id="success-notification" class="notification notification-success">
    <i class="fas fa-check-circle"></i> Voting system has been successfully reset!
</div>

<!-- Error Notification -->
<div id="error-notification" class="notification notification-error">
    <i class="fas fa-exclamation-circle"></i> <span id="error-text">An error occurred.</span>
</div>

<script>
    $(document).ready(function() {
        // Show notification if reset was successful
        <?php if ($success_message): ?>
            showNotification("#success-notification");
        <?php endif; ?>
        
        // Show notification if there was an error
        <?php if ($error_message): ?>
            $("#error-text").text("<?php echo htmlspecialchars($error_message); ?>");
            showNotification("#error-notification");
        <?php endif; ?>
        
        // When the reset button is clicked, show the modal
        $("#reset-btn").click(function(e) {
            e.preventDefault();
            $("#passkey-modal").css("display", "block");
            $("#passkey-input").focus();
        });
        
        // When the close button is clicked, hide the modal
        $(".close").click(function() {
            $("#passkey-modal").css("display", "none");
            $("#passkey-input").val("");
            $("#passkey-error").text("");
        });
        
        // When clicking outside the modal, hide it
        $(window).click(function(e) {
            if ($(e.target).is("#passkey-modal")) {
                $("#passkey-modal").css("display", "none");
                $("#passkey-input").val("");
                $("#passkey-error").text("");
            }
        });
        
        // Handle verify button click
        $("#verify-btn").click(function() {
            $("#passkey-form").submit();
        });
        
        // Handle passkey submission
        $("#passkey-form").submit(function(e) {
            e.preventDefault();
            var passkey = $("#passkey-input").val();
            
            if (!passkey) {
                $("#passkey-error").text("Please enter your passkey.");
                return;
            }
            
            $("#verify-btn").prop("disabled", true).html('<i class="fas fa-spinner fa-spin"></i> Verifying...');
            
            $.ajax({
                type: "POST",
                url: "validate_passkey.php",
                data: { passkey: passkey },
                success: function(response) {
                    if (response.trim() === "success") {
                        // If passkey is valid, show processing message
                        $("#passkey-error").removeClass("error-message").addClass("success-message").text("Passkey validated. Resetting system...");
                        
                        // Short delay to show the success message before submitting
                        setTimeout(function() {
                            $("#confirm-reset-form").submit();
                        }, 1000);
                    } else {
                        $("#passkey-error").text("Invalid passkey. Please try again.");
                        $("#verify-btn").prop("disabled", false).html('<i class="fas fa-check-circle"></i> Verify & Reset');
                    }
                },
                error: function() {
                    $("#passkey-error").text("Error validating passkey. Please try again.");
                    $("#verify-btn").prop("disabled", false).html('<i class="fas fa-check-circle"></i> Verify & Reset');
                }
            });
        });
        
        // Function to show and hide notifications
        function showNotification(selector) {
            $(selector).addClass("show");
            setTimeout(function() {
                $(selector).removeClass("show");
            }, 5000);
        }
    });
</script>

</body>
</html>