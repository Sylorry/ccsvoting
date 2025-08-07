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
        "President",
        "Vice President",
        "Secretary",
        "Treasurer",
        "Auditor",
        "PRO",
        "1st Year Representative",
        "2nd Year Representative",
        "3rd Year Representative"
    ];

    // Fetch candidates from the student_candidates table
    $candidates = [];
    $query = "SELECT position, candidate_name, year_level, program, party FROM student_candidates";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $candidates[$row['position']][] = $row; // Store candidates grouped by position
        }
    } else {
        $candidates = []; // No candidates found or query failed
        // Optional: Uncomment the next line for debugging
        // echo "Query error: " . $conn->error;
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
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome CDN -->
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #2c3e50, #27ae60);
            color: white;
            text-align: center;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: rgba(0, 0, 0, 0.8);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }
        h2 {
            margin-bottom: 20px;
            font-size: 2rem;
        }
        h3 {
            margin: 20px 0 10px;
            font-size: 1.5rem;
            color: #f39c12; /* Highlight color for position headings */
        }
        table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
            background: #ecf0f1;
            color: black;
            border-radius: 5px;
            overflow: hidden;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #2c3e50;
            color: white;
            font-weight: bold;
        }
        tr:hover {
            background-color: #bdc3c7;
        }
        .buttons {
            margin-top: 20px;
        }
        .button {
            display: inline-block;
            padding: 12px 25px;
            margin: 10px;
            background-color: #27ae60;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.3s;
            font-size: 1rem;
        }
        .button:hover {
            background-color: #2ecc71;
            transform: scale(1.05);
        }
        .icon {
            margin-right: 8px; /* Space between icon and text */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Vote for Candidates</h2>

        <div class="buttons">
            <a href="index.php" class="button">Home</a>
        </div>

        <?php 
        $hasCandidates = false;
        foreach ($position_order as $position):
            if (!empty($candidates[$position])): 
                $hasCandidates = true; ?>
                <h3><?php echo htmlspecialchars($position); ?></h3>
                <table>
                    <thead>
                        <tr>
                            <th>Candidate Name</th>
                            <th>Year Level</th>
                            <th>Program</th>
                            <th>Party</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($candidates[$position] as $candidate): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($candidate['candidate_name']); ?></td>
                                <td><?php echo htmlspecialchars($candidate['year_level']); ?></td>
                                <td><?php echo htmlspecialchars($candidate['program']); ?></td>
                                <td><?php echo htmlspecialchars($candidate['party']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
        <?php 
            endif;
        endforeach; 
        
        if (!$hasCandidates): ?>
            <p>No candidates found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
