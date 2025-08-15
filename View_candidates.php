<?php
session_start();

// Database connection details
$servername = "localhost"; 
$username = "root";       
$password = "";            
$database = "voting";        

// Create database connection
try {
    $conn = new mysqli($servername, $username, $password, $database);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Define the desired order of positions
    $position_order = [
        "PRESIDENT",
        "INTERNAL VICE-PRESIDENT",
        "EXTERNAL VICE-PRESIDENT",
        "SECRETARY",
        "TREASURER",
        "AUDITOR",
        "PUBLIC INFORMATION OFFICER"
    ];

    // Fetch candidates from the student_candidates table with all required fields
    $candidates = [];
    $query = "SELECT 
                student_id,
                candidate_name,
                program,
                year_level,
                position,
                party,
                vote_tally
              FROM student_candidates
              ORDER BY position, candidate_name";
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
    <link rel="icon" type="image/png" href="images/ccs.png">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: #EEEEEE;
            color: white;
            text-align: center;
            margin: 0;
            padding: 10px;
            padding-top: 20px;
        }
        
        .container {
            width: 100%;
            max-width: 1600px;
            margin: 20px auto 50px;
            background: #ffffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            min-height: 70vh;
            max-height: 85vh;
        }
        
        .container::before {
            content: "";
            background-image: url('images/ccs.png');
            background-repeat: no-repeat;
            background-position: center;
            background-size: 500px;
            opacity: 0.12;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 500px;
            height: 500px;
            z-index: 0;
            pointer-events: none;
        }
        h2 {
            margin-bottom: 20px;
            font-size: 1.8rem;
            color: black;
        }
        h3 {
            margin: 15px 0 8px;
            font-size: 1.3rem;
            color: #060606ff;
        }
        table {
            width: 100%;
            margin: 15px 0;
            border-collapse: collapse;
            background: #ecf0f1;
            color: black;
            border-radius: 5px;
            overflow: hidden;
            font-size: 0.9rem;
        }
        th, td {
            border: 1px solid #000000ff;
            padding: 8px;
            text-align: center;
            word-wrap: break-word;
        }
        th {
            background-color: #27391C;
            color: white;
            font-weight: bold;
            font-size: 0.85rem;
        }

        .buttons {
            margin-top: 15px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #18230F;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 0.9rem;
            min-width: 120px;
        }
        .icon {
            margin-right: 5px;
        }
        
        /* Responsive Design */
        @media screen and (max-width: 768px) {
            .container {
                width: 98%;
                padding: 15px;
                margin: 10px auto;
            }
            h2 {
                font-size: 1.5rem;
            }
            h3 {
                font-size: 1.2rem;
            }
            table {
                font-size: 0.8rem;
            }
            th, td {
                padding: 6px;
                font-size: 0.75rem;
            }
            .button {
                padding: 8px 16px;
                font-size: 0.8rem;
                min-width: 100px;
            }
        }
        
        @media screen and (max-width: 480px) {
            .container {
                width: 100%;
                padding: 10px;
                margin: 5px auto;
                border-radius: 0;
            }
            h2 {
                font-size: 1.3rem;
            }
            h3 {
                font-size: 1.1rem;
            }
            table {
                font-size: 0.7rem;
            }
            th, td {
                padding: 4px;
                font-size: 0.7rem;
            }
            .button {
                padding: 6px 12px;
                font-size: 0.75rem;
                min-width: 90px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div style="align-self: flex-start; margin-bottom: 20px;">
            <a href="index.php" class="button home-button">Back to Home</a>
        </div>
        <h2>LIST OF CANDIDATES</h2>

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
