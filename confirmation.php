<?php
// Start the session to use session variables if needed
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            text-align: center;
            padding: 50px;
        }
        h1 {
            color: #4CAF50; /* Green accent */
        }
        .confirmation-message {
            font-size: 1.2em;
            margin: 20px 0;
        }
        a {
            text-decoration: none;
            color: white;
            background-color: #4CAF50; /* Green accent */
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        a:hover {
            background-color: #45a049; /* Darker green on hover */
        }
    </style>
</head>
<body>
    <h1>VOTE NOW</h1>
    <div class="confirmation-message">
        "You're Student ID has been verified, you can Vote now!"
    </div>
    <a href="vote_tally.php">Proceed to Vote</a> <!-- Change to your main page -->
</body>
</html>