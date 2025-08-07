<?php
session_start();

// Database connection details
$servername = "localhost"; // or 127.0.0.1
$username = "root";        // default XAMPP username
$password = "";            // default XAMPP password is empty
$database = "voting";         // make sure this DB exists in phpMyAdmin

// Create database connection
try {
    $conn = new mysqli($servername, $username, $password, $database);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Define the desired order of positions
    $position_order = [
        "1st Year Representative",
        "President",
        "Vice President",
        "Secretary",
        "Treasurer",
        "Auditor",
        "PIO",
        "1ST YEAR CLASS MAYOR",
        "2ND YEAR CLASS MAYOR",
        "3RD YEAR CLASS MAYOR"
    ];

    // Fetch candidates from the student_candidates table
    $candidates = [];
    $query = "SELECT position, student_id, candidate_name, year_level, program, party FROM student_candidates";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $candidates[$row['position']][] = $row; // Store candidates grouped by position
        }
    } else {
        $candidates = []; // No candidates found or query failed
    }
} catch (Exception $e) {
    $error = "System Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Candidates</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #2c3e50, #27ae60);
            color: white;
            text-align: center;
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 900px;
            margin: auto;
            background: rgba(0, 0, 0, 0.8);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
        }
        h2 {
            margin-bottom: 25px;
            font-size: 2.2rem;
            position: relative;
            padding-bottom: 10px;
        }
        h2:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background-color: #27ae60;
        }
        h3 {
            background: rgba(39, 174, 96, 0.2);
            padding: 10px;
            border-radius: 8px;
            margin-top: 30px;
            font-size: 1.5rem;
        }
        .table-container {
            overflow-x: auto;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border-radius: 12px;
            margin: 20px 0 35px;
        }
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: #fff;
            color: #333;
            overflow: hidden;
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        th {
            background-color: #2c3e50;
            color: white;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
        }
        tr:last-child td {
            border-bottom: none;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #e8f4f0;
            transition: background-color 0.3s ease;
        }
        .button {
            display: inline-block;
            padding: 12px 25px;
            margin: 15px 5px;
            background-color: #27ae60;
            color: white;
            text-decoration: none;
            border-radius: 50px;
            transition: all 0.3s ease;
            font-size: 1rem;
            font-weight: 500;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(39, 174, 96, 0.3);
        }
        .button i {
            margin-right: 8px;
        }
        .button:hover {
            background-color: #2ecc71;
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(39, 174, 96, 0.4);
        }
        .button.delete {
            background-color: #e74c3c;
            box-shadow: 0 4px 10px rgba(231, 76, 60, 0.3);
        }
        .button.delete:hover {
            background-color: #c0392b;
            box-shadow: 0 6px 15px rgba(231, 76, 60, 0.4);
        }
        .action-btn {
            padding: 6px 12px;
            border-radius: 4px;
            background-color: #e74c3c;
            color: white;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.85rem;
        }
        .action-btn:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
        }

        /* Enhanced Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            animation: fadeIn 0.3s;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .modal-content {
            background: white;
            color: #333;
            margin: 10% auto;
            width: 350px;
            padding: 30px;
            text-align: center;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
            position: relative;
            animation: slideIn 0.4s;
        }
        @keyframes slideIn {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .close {
            position: absolute;
            right: 15px;
            top: 10px;
            color: #999;
            font-size: 24px;
            cursor: pointer;
            transition: color 0.3s;
        }
        .close:hover {
            color: #e74c3c;
        }
        .modal h3 {
            margin-top: 0;
            color: #2c3e50;
            background: none;
        }
        .modal input[type="password"] {
            width: 90%;
            padding: 12px;
            margin: 20px 0;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: all 0.3s;
        }
        .modal input[type="password"]:focus {
            border-color: #27ae60;
            box-shadow: 0 0 0 2px rgba(39, 174, 96, 0.2);
            outline: none;
        }
        #errorMessage {
            color: #e74c3c;
            margin-top: 15px;
            font-size: 14px;
            animation: shake 0.5s;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        .no-candidates {
            background: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 10px;
            margin: 30px 0;
            font-size: 1.2rem;
        }
        
        /* Alert message styling */
        .alert {
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            position: relative;
            animation: fadeIn 0.5s;
        }
        .alert-success {
            background-color: rgba(46, 204, 113, 0.2);
            border-left: 4px solid #2ecc71;
        }
        .alert-error {
            background-color: rgba(231, 76, 60, 0.2);
            border-left: 4px solid #e74c3c;
        }
        .alert-close {
            position: absolute;
            right: 10px;
            top: 10px;
            cursor: pointer;
            color: white;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .container {
                padding: 20px 15px;
            }
            th, td {
                padding: 10px 8px;
                font-size: 0.9rem;
            }
            .modal-content {
                width: 85%;
                padding: 25px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>List of Candidates</h2>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['message_type']; ?>">
                <?php echo $_SESSION['message']; ?>
                <span class="alert-close">&times;</span>
            </div>
            <?php 
            // Clear message after displaying
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
            ?>
        <?php endif; ?>

        <div>
            <a href="vote.php" class="button"><i class="fas fa-home"></i> Home</a>
            <button class="button delete" id="openModalAll"><i class="fas fa-trash"></i> Remove All Candidates</button>
        </div>

        <div id="deleteModalAll" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h3><i class="fas fa-exclamation-triangle"></i> Delete All Candidates</h3>
                <p>Are you sure you want to remove <strong>ALL</strong> candidates? This action cannot be undone.</p>
                <input type="password" id="passkeyAll" placeholder="Enter Admin Passkey" autocomplete="off">
                <button class="button delete" id="confirmDeleteAll"><i class="fas fa-check"></i> Confirm Delete All</button>
                <p id="errorMessageAll" style="display: none;">Invalid passkey. Please try again.</p>
            </div>
        </div>

        <div id="deleteModalSingle" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h3><i class="fas fa-user-minus"></i> Delete Candidate</h3>
                <p>Are you sure you want to remove this candidate?</p>
                <input type="password" id="passkeySingle" placeholder="Enter Admin Passkey" autocomplete="off">
                <input type="hidden" id="candidateId" value="">
                <button class="button delete" id="confirmDeleteSingle"><i class="fas fa-check"></i> Confirm Delete</button>
                <p id="errorMessageSingle" style="display: none;">Invalid passkey. Please try again.</p>
            </div>
        </div>

        <?php 
        $hasCandidates = false;
        foreach ($position_order as $position):
            if (!empty($candidates[$position])): 
                $hasCandidates = true; ?>
                <h3><i class="fas fa-user-tie"></i> <?php echo htmlspecialchars($position); ?></h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Candidate Name</th>
                                <th>Year Level</th>
                                <th>Program</th>
                                <th>Party</th>
                                <th>Actions
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($candidates[$position] as $candidate): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($candidate['candidate_name']); ?></td>
                                    <td><?php echo htmlspecialchars($candidate['year_level']); ?></td>
                                    <td><?php echo htmlspecialchars($candidate['program']); ?></td>
                                    <td><?php echo htmlspecialchars($candidate['party']); ?></td>
                                    <td>
                                        <button class="action-btn delete-candidate" data-id="<?php echo $candidate['student_id']; ?>">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
        <?php 
            endif;
        endforeach; 
        
        if (!$hasCandidates): ?>
            <div class="no-candidates">
                <i class="fas fa-info-circle fa-2x"></i>
                <p>No candidates have been registered yet.</p>
            </div>
        <?php endif; ?>
    </div>

    <script>
        $(document).ready(function () {
            // Alert close button
            $(".alert-close").click(function() {
                $(this).parent().fadeOut();
            });
            
            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                $(".alert").fadeOut();
            }, 5000);
            
            // Delete All Candidates Modal functionality
            $("#openModalAll").click(function () {
                $("#deleteModalAll").fadeIn();
                $("#passkeyAll").focus();
            });

            // Close modals when clicking on close button
            $(".close").click(function () {
                $(this).closest(".modal").fadeOut();
                resetModalInputs();
            });

            // Close modals when clicking outside
            $(window).click(function (event) {
                if ($(event.target).hasClass("modal")) {
                    $(".modal").fadeOut();
                    resetModalInputs();
                }
            });

            // Function to reset modal inputs
            function resetModalInputs() {
                $("#passkeyAll").val("");
                $("#passkeySingle").val("");
                $("#candidateId").val("");
                $("#errorMessageAll").hide();
                $("#errorMessageSingle").hide();
            }

            // Submit on Enter key for all modals
            $("#passkeyAll, #passkeySingle").keypress(function (e) {
                if (e.which === 13) {
                    $(this).closest(".modal-content").find("button").click();
                }
            });

            // Individual delete button click
            $(".delete-candidate").click(function() {
                var candidateId = $(this).data("id");
                $("#candidateId").val(candidateId);
                $("#deleteModalSingle").fadeIn();
                $("#passkeySingle").focus();
            });

            // Confirm delete all candidates
            $("#confirmDeleteAll").click(function () {
                var passkey = $("#passkeyAll").val();
                if (!passkey) {
                    $("#errorMessageAll").text("Please enter a passkey").show();
                    $("#passkeyAll").focus();
                    return;
                }
                
                $.post("validate_passkey.php", { passkey: passkey }, function (response) {
                    if (response === "success") {
                        window.location.href = "delete_candidates.php?all=1";
                    } else {
                        $("#errorMessageAll").text("Invalid passkey. Please try again.").show();
                        $("#passkeyAll").val("").focus();
                    }
                });
            });

            // Confirm delete single candidate
           // Confirm delete single candidate
$("#confirmDeleteSingle").click(function () {
    var passkey = $("#passkeySingle").val();
    var candidateId = $("#candidateId").val(); // Corrected selector
    
    if (!passkey) {
        $("#errorMessageSingle").text("Please enter a passkey").show();
        $("#passkeySingle").focus();
        return;
    }
    
    $.post("validate_passkey.php", { passkey: passkey }, function (response) {
        if (response === "success") {
            window.location.href = "delete_candidates.php?id=" + candidateId;
        } else {
            $("#errorMessageSingle").text("Invalid passkey. Please try again.").show();
            $("#passkeySingle").val("").focus();
        }
    });
});
        });
    </script>
</body>
</html>