<?php
session_start();
require_once 'db.php';

try {
    $conn = new mysqli($servername, $username, $password, $database);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Fetching election winners
    $query = "SELECT position, candidate_name, SUM(vote_tally) AS vote_tally 
              FROM votes 
              GROUP BY position, candidate_name 
              HAVING vote_tally > 0 
              ORDER BY FIELD(position, 
                'DEPARTMENT PRESIDENT', 
                'VICE PRESIDENT', 
                'SECRETARY', 
                'TREASURER', 
                'AUDITOR', 
                'PIO', 
                '1st YR. CLASS REPRESENTATIVE', 
                '2ND YR. CLASS REPRESENTATIVE', 
                '3RD YR. CLASS REPRESENTATIVE'), 
                vote_tally DESC";

    $result = $conn->query($query);
    
    if (!$result) {
        throw new Exception("Error fetching results: " . $conn->error);
    }

    $winners = [];
    while ($row = $result->fetch_assoc()) {
        $position = $row['position'];
        if (!isset($winners[$position]) || $row['vote_tally'] > $winners[$position]['vote_tally']) {
            $winners[$position] = $row;
        }
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
    <title>Final Results</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #FFFDD0;
            color: #000;
            text-align: center;
            padding: 20px;
        }

        .container {
            max-width: 90%;
            margin: auto;
            padding: 15px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            position: relative;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-align: center;
            margin-bottom: 10px;
        }

        .header img {
            height: 55px;
            max-height: 70px;
        }

        .header-content {
            text-align: center;
            flex-grow: 1;
        }

        .header-content h2 {
            font-size: 1rem;
            margin: 0;
            font-weight: bold;
            color: #024d2f;
        }

        .header-content h3 {
            font-size: 0.75rem;
            margin: 2px 0 3px;
            font-weight: bold;
            color: #024d2f;
        }

        .header-content h1 {
            font-size: 1.2rem;
            margin: 5px 0 3px;
            font-weight: bold;
            color: #013220;
        }

        .buttons {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .buttons a, .buttons button {
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 14px;
            text-decoration: none;
            color: white;
            font-weight: bold;
            transition: background 0.3s;
            border: none;
            cursor: pointer;
        }

        .back-button {
            background: #c0392b;
        }

        .print-button {
            background: #2980b9;
        }

        .back-button:hover {
            background: #a93226;
        }

        .print-button:hover {
            background: #21618c;
        }

        .results {
            text-align: center;
            font-size: 18px;
        }

        .results span {
            color: green;
            font-weight: bold;
        }

        .results ul {
            list-style-type: none;
            padding: 0;
        }

        /* Watermark for print */
        @media print {
            .buttons { display: none; }
            body { background: white; color: black; }
            .container { background: none; box-shadow: none; position: relative; }
            .header-content h2, .header-content h3, .header-content h1 { color: black; }
            .header img { height: 70px; }
            .watermark {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                opacity: 0.2;
                z-index: -1;
                width: 50%;
                max-width: 300px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="dlsjbc1.png" alt="Left Logo">
            <div class="header-content">
                <h2>DE LA SALLE JOHN BOSCO COLLEGE</h2>
                <h3>La Salle Drive John Bosco District</h3>
                <h1>OFFICIAL OFFICERS</h1>
                <h3>CSS PSEUDOCODE.COM SOCIETY</h3>
            </div>
            <img src="ccs.png" alt="Right Logo">
        </div>

        <div class="buttons">
            <a href="results.php" class="back-button">← Back to Results</a>
            <button class="print-button" onclick="window.print()">🖨 Print</button>
        </div>

        <!-- Watermark for print -->
        <img src="ccs1.png" alt="Watermark" class="watermark">

        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php else: ?>
            <div class="results">
                <ul>
                    <?php foreach ($winners as $position => $winner): ?>
                        <li><?php echo htmlspecialchars($position); ?>: 
                            <span>
                                <?php echo htmlspecialchars($winner['candidate_name']); ?>
                            </span> (<?php echo $winner['vote_tally']; ?> votes)
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
