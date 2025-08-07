<?php
$servername = "127.0.0.1";
$username = "u878574291_ccs1";
$password = "CCSPseudocode01";
$dbname = "u878574291_ccs";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Fetch candidates grouped by position
$sql = "SELECT candidate_name, position, party, vote_tally FROM student_candidates ORDER BY position, vote_tally DESC";
$result = $conn->query($sql);
$candidates = [];
while ($row = $result->fetch_assoc()) {
    $candidates[$row['position']][] = $row;
}
// Fetch total voters and those who have voted
$sql_voters = "SELECT COUNT(*) as total_voters, SUM(has_voted = 1) as voted FROM students";
$result_voters = $conn->query($sql_voters);
$voters_data = $result_voters->fetch_assoc();
$total_voters = $voters_data['total_voters'];
$voted = $voters_data['voted'];
$voted_percentage = ($total_voters > 0) ? ($voted / $total_voters) * 100 : 0;
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>College of Computer Studies Election Result</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, #2c3e50, #27ae60);
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }
        .buttons {
            display: flex;
            gap: 10px;
        }
        .btn {
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            display: inline-block;
        }
        .btn-print {
            background-color: #2196F3;
        }
        .btn-back {
            background-color: #607D8B;
        }
        .stats {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .stat-box {
            flex: 1;
            min-width: 200px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px; 
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 12px; 
            text-align: left; 
        }
        th { 
            background-color: #f2f2f2; 
            font-weight: bold;
        }
        .position-title {
            background-color: #e7f3ff;
            padding: 10px;
            margin-top: 25px;
            border-radius: 4px;
            font-size: 18px;
        }
        .leading {
            background-color: #e8f5e9;
            font-weight: bold;
            position: relative;
        }
        .leading::after {
            content: "LEADING";
            position: absolute;
            right: 10px;
            color: #4CAF50;
            font-size: 12px;
        }
        .tie {
            background-color: #fff8e1;
            font-weight: bold;
            position: relative;
        }
        .tie::after {
            content: "TIE";
            position: absolute;
            right: 10px;
            color: #FF9800;
            font-size: 12px;
        }
        .progress-container {
            width: 100%;
            background-color: #ddd;
            border-radius: 4px;
            margin-top: 5px;
        }
        .progress-bar {
            height: 10px;
            background-color: #4CAF50;
            border-radius: 4px;
        }
        footer {
            margin-top: 30px;
            text-align: center;
            color: #777;
            font-size: 12px;
            position: relative;
            top: 10px;
            padding: 20px 0;
            border-top: 1px solid #eee;
        }
        .signature-left, .signature-right {
            position: absolute;
            bottom: 10;
            font-size: 14px;
            color: #333;
            display: none;
        }
        .signature-left {
            left: 20px;
        }
        .signature-right {
            right: 20px;
        }
        @media print {
            .btn, .no-print {
                display: none;
            }
            body {
                background-color: white;
                padding: 0;
            }
            .container {
                box-shadow: none;
                padding: 0;
            }
            .stats {
                border: 1px solid #ddd;
                padding: 10px;
            }
            .leading::after, .tie::after {
                position: static;
                margin-left: 10px;
            }
             .signature-left, .signature-right {
                display: block;
             }
        }

        /* Footer with signatures */
        
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>CCS Officers Election Result</h1>
            <div class="buttons no-print">
                <button class="btn btn-print" onclick="window.print()">Print Results</button>
                <a href="vote.php" class="btn btn-back">Back to Voting</a>
            </div>
        </div>

        <div class="stats">
            <div class="stat-box">
                <h3>Total Voters</h3>
                <h2><?php echo $total_voters; ?></h2>
            </div>
            <div class="stat-box">
                <h3>Votes Cast</h3>
                <h2><?php echo $voted; ?></h2>
                <div class="progress-container">
                    <div class="progress-bar" style="width:<?php echo round($voted_percentage, 2); ?>%"></div>
                </div>
                <p><?php echo round($voted_percentage, 2); ?>% participation</p>
            </div>
        </div>

        <?php foreach ($candidates as $position => $list): ?>
            <h3 class="position-title"><?php echo htmlspecialchars($position); ?></h3>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Party</th>
                    <th>Vote Tally</th>
                </tr>
                <?php 
                // Find the maximum vote tally for this position
                $max_votes = 0;
                foreach ($list as $candidate) {
                    if ($candidate['vote_tally'] > $max_votes) {
                        $max_votes = $candidate['vote_tally'];
                    }
                }

                // Count how many have the max votes (to determine ties)
                $max_votes_count = 0;
                foreach ($list as $candidate) {
                    if ($candidate['vote_tally'] == $max_votes) {
                        $max_votes_count++;
                    }
                }

                foreach ($list as $index => $candidate): 
                    $row_class = '';
                    if ($candidate['vote_tally'] == $max_votes) {
                        if ($max_votes_count > 1) {
                            $row_class = 'tie';
                        } else {
                            $row_class = 'leading';
                        }
                    }
                ?>
                    <tr class="<?php echo $row_class; ?>">
                        <td><?php echo htmlspecialchars($candidate['candidate_name']); ?></td>
                        <td><?php echo htmlspecialchars($candidate['party']); ?></td>
                        <td><?php echo htmlspecialchars($candidate['vote_tally']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endforeach; ?>

        <footer>
           <div class="signature-container">
    <div class="signature-left"><br>
        ________________ <br>
        <strong>PSEUDO President</strong> <br>
        OverPrinted Name <br>
        Signature
    </div>
    <div class="signature-right">
        ________________ <br>
        <strong>CCS Dean</strong> <br>
        OverPrinted Name <br>
        Signature
    </div>
</div>

            <?php
            date_default_timezone_set('Asia/Manila');
            echo "Results last updated: " . date('F j, Y, g:i a');
            ?>
        </footer>
    </div>

    <script>
        // Add any JavaScript functionality here if needed
    </script>
</body>
</html>