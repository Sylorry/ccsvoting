<?php
// Database connection
$servername = "localhost"; // or 127.0.0.1
$username = "root";        // default XAMPP username
$password = "";            // default XAMPP password is empty
$database = "voting";         // make sure this DB exists in phpMyAdmin

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assuming you have the student_id from the query parameter


// Redirect to a confirmation page or back to the main page
header("Location: confirmation.php"); // Change to your confirmation page
exit();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Candidates List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #4CAF50; /* Green accent */
        }
        .position-section {
            margin-bottom: 20px; /* Space between sections */
        }
        table {
            width: 100%; /* Set table width to 100% */
            border-collapse: collapse;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left; /* Default alignment */
        }
        th {
            background-color: #4CAF50; /* Green accent for header */
            color: white;
        }
        td {
            transition: background-color 0.3s; /* Smooth transition for hover effect */
        }
        td:hover {
            background-color: #f1f1f1; /* Light gray on hover */
        }
        /* Set equal widths for columns */
        th:nth-child(1), td:nth-child(1) {
            width: 10%; /* Select */
        }
        th:nth-child(2), td:nth-child(2) {
            width: 30%; /* Candidate Name */
        }
        th:nth-child(3), td:nth-child(3) {
            width: 30%; /* Program */
        }
        th:nth-child(4), td:nth-child(4) {
            width: 30%; /* Year Level */
        }
        .position-header {
            font-size: 1.5em;
            margin-top: 20px;
            color: #black; /* Green accent */
            text-align: center; /* Center the position header */
        }
        .error {
            color: red;
            text-align: center;
        }
        button {
            background-color: #4CAF50; /* Green accent */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #45a049; /* Darker green on hover */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Student Candidates List</h1>
        
        <?php if (isset($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (isset($candidates) && is_array($candidates)): ?>
            <form method="post" action="vote_tally.php"> <!-- Change action to your processing script -->
                <?php foreach ($candidates as $position => $candidatesList): ?>
                    <div class="position-section">
                        <div class="position-header"><?php echo htmlspecialchars($position); ?></div>
                        <table>
                            <thead>
                                <tr>
                                    <th>Select</th>
                                    <th>Candidate Name</th>
                                    <th>Program</th>
                                    <th>Year Level</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($candidatesList as $candidate): ?>
                                    <tr>
                                        <td>
                                            <input type="radio" name="<?php echo htmlspecialchars($position); ?>" value="<?php echo htmlspecialchars($candidate['candidate_name']); ?>">
                                        </td>
                                        <td><?php echo htmlspecialchars($candidate['candidate_name']); ?></td>
                                        <td><?php echo htmlspecialchars($candidate['program']); ?></td>
                                        <td><?php echo htmlspecialchars($candidate['year_level']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endforeach; ?>
                <div style="text-align: center;">
                    <button type="submit">Submit Selection</button>
                </div>
            </form>
        <?php else: ?>
            <p>No candidates found.</p>
        <?php endif; ?>
    </div>
</body>
</html>