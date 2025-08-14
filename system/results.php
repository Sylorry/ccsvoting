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

// Fetch the tally of votes
$query = "SELECT vote_option, COUNT(*) as vote_count FROM votes GROUP BY vote_option ORDER BY vote_count DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote Results</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: #f5f5f5;
            color: #333;
            text-align: center;
        }
        .container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
            width: 90%;
            max-width: 800px;
            margin: 20px auto;
        }
        h1 {
            font-size: 32px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            padding: 15px;
            text-align: left;
            border: 1px solid #ddd;
            font-size: 18px;
        }
        table th {
            background-color: #2ecc71;
            color: white;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Vote Results</h1>
    <table>
        <thead>
            <tr>
                <th>Vote Option</th>
                <th>Vote Count</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['vote_option']) . "</td>
                            <td>" . htmlspecialchars($row['vote_count']) . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No votes have been cast yet.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <a href="vote.php?id=<?php echo htmlspecialchars($student_id); ?>" style="display: inline-block; margin-top: 20px; padding: 10px 20px; background-color: #3498db; color: white; text-decoration: none; border-radius: 8px;">Back to Voting</a>
</div>
</body>
</html>

<?php mysqli_close($conn); ?>