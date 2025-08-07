<?php
session_start();
require_once 'db.php'; // Include database connection

try {
    $conn = new mysqli($servername, $username, $password, $database);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // If form is submitted, store selection in session
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $_SESSION['selected_votes'] = [];

        foreach ($_POST as $position => $votes) {
            $_SESSION['selected_votes'][$position] = is_array($votes) ? $votes : [$votes];
        }

        header("Location: review_candidates.php"); // Redirect to review page
        exit();
    }

    // Fetch unique candidates from database
    $candidates = [];
    $query = "SELECT s.student_id, s.position, s.candidate_name, s.program, s.year_level, s.vote_tally 
              FROM student_candidates s
              ORDER BY 
                CASE s.position
                    WHEN 'PRESIDENT' THEN 1
                    WHEN 'VICE PRESIDENT' THEN 2
                    WHEN 'SECRETARY' THEN 3
                    WHEN 'TREASURER' THEN 4
                    WHEN 'AUDITOR' THEN 5
                    WHEN 'PIO' THEN 6
                    WHEN '1ST YEAR CLASS MAYOR' THEN 7
                    WHEN '2ND YEAR CLASS MAYOR' THEN 8
                    WHEN '3RD YEAR CLASS MAYOR' THEN 9
                    ELSE 10
                END,
                s.vote_tally ASC";

    $result = $conn->query($query);

    // Check if query was successful
    if ($result === false) {
        throw new Exception("Query failed: " . $conn->error);
    }

    // Prevent duplicate candidates using an associative array
    $unique_candidates = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $key = $row['position'] . '-' . $row['candidate_name']; // Unique key
            if (!isset($unique_candidates[$key])) {
                $unique_candidates[$key] = true; // Mark as added
                $candidates[$row['position']][] = [
                    'student_id' => $row['student_id'],
                    'candidate_name' => $row['candidate_name'],
                    'program' => $row['program'],
                    'year_level' => $row['year_level']
                ];
            }
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
    <title>Vote for Candidates</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<style>
        body {
            background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
            color: #1b5e20;
            font-family: 'Arial', sans-serif;
        }
        .container {
            max-width: 900px;
            margin: 30px auto;
            background: #ffffff;
            color: #2e7d32;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
        }
        h1 {
            text-align: center;
            color: #1b5e20;
            font-weight: bold;
        }
        .position-header {
            font-size: 1.5em;
            font-weight: bold;
            color: #ffffff;
            text-transform: uppercase;
            margin-bottom: 10px;
            text-align: center;
            padding: 10px;
            background: #2e7d32;
            border-radius: 6px;
        }
        .candidate-card {
            display: flex;
            align-items: center;
            background: #e8f5e9;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.12);
            transition: transform 0.3s, background 0.3s;
            color: #1b5e20;
            font-weight: bold;
        }
        .candidate-card:hover {
            transform: scale(1.02);
            background: #c8e6c9;
        }
        .candidate-card input {
            margin-right: 15px;
        }
        .candidate-card.selected {
            background: #388e3c;
            color: #ffffff;
        }
        .btn-vote {
            display: block;
            width: 100%;
            background: #388e3c;
            color: #fff;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            transition: background 0.3s;
        }
        .btn-vote:hover {
            background: #2e7d32;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Vote for Student Candidates</h1>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger text-center"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (!empty($candidates)): ?>
            <form method="post">
        
                <?php foreach ($candidates as $position => $candidatesList): ?>
                    <div class="position-section">
                        <div class="position-header"><?php echo htmlspecialchars($position); ?></div>
                        <?php 
                        $isMultipleChoice = stripos($position, "TAE") !== false;
                        ?>
                        <?php foreach ($candidatesList as $candidate): ?>
                            <label class="candidate-card">
                                <input type="<?php echo $isMultipleChoice ? 'checkbox' : 'radio'; ?>" 
                                       name="<?php echo htmlspecialchars($position); ?><?php echo $isMultipleChoice ? '[]' : ''; ?>" 
                                       value="<?php echo htmlspecialchars($candidate['student_id']); ?>" 
                                       class="vote-checkbox"
                                       data-position="<?php echo htmlspecialchars($position); ?>">
                                <div>
                                    <strong><?php echo htmlspecialchars($candidate['candidate_name']); ?></strong><br>
                                    <small><?php echo htmlspecialchars($candidate['program']); ?> - Year <?php echo htmlspecialchars($candidate['year_level']); ?></small>
                                </div>
                            </label>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
                <button type="submit" class="btn-vote">Submit</button>
            </form>
        <?php else: ?>
            <p class="text-center">No candidates found.</p>
        <?php endif; ?>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
    // Retrieve stored votes from localStorage
    let storedVotes = JSON.parse(localStorage.getItem("votes")) || {};

    // Loop through all vote checkboxes/radio buttons
    document.querySelectorAll(".vote-checkbox").forEach(function(input) {
        let position = input.getAttribute("data-position");
        let value = input.value;

        // Restore selections from localStorage
        if (storedVotes[position]) {
            if (Array.isArray(storedVotes[position])) {
                // For checkboxes (multiple selections)
                if (storedVotes[position].includes(value)) {
                    input.checked = true;
                }
            } else {
                // For radio buttons (single selection)
                if (storedVotes[position] === value) {
                    input.checked = true;
                }
            }
        }

        // Add event listener for changes
        input.addEventListener("change", function() {
            if (input.type === "radio") {
                // For radio buttons, store a single value
                storedVotes[position] = value;
            } else if (input.type === "checkbox") {
                // For checkboxes, store an array of values
                if (!storedVotes[position]) {
                    storedVotes[position] = [];
                }
                if (input.checked) {
                    storedVotes[position].push(value);
                } else {
                    storedVotes[position] = storedVotes[position].filter(v => v !== value);
                }
            }

            // Update localStorage with the latest selections
            localStorage.setItem("votes", JSON.stringify(storedVotes));
        });
    });

    // Log selected candidate_ids when the form is submitted
    document.querySelector("form").addEventListener("submit", function(event) {
        // Prevent the form from submitting immediately (for debugging purposes)
        event.preventDefault();

        // Collect all selected candidate_ids
        let selectedCandidateIds = [];
        document.querySelectorAll(".vote-checkbox:checked").forEach(function(input) {
            selectedCandidateIds.push(input.value);
        });

        // Log the selected candidate_ids to the console
        console.log("Selected Candidate IDs:", selectedCandidateIds);

        // Clear localStorage when the form is submitted
        localStorage.removeItem("votes");

        // Submit the form programmatically after logging
        this.submit();
    });
});
</script>

</body>
</html>
