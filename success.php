<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote Submitted</title>
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
        .message {
            font-size: 1.2em;
            margin: 20px 0;
        }
        a {
            text-decoration: none;
            color: #4CAF50; /* Green accent */
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Thank You for Voting!</h1>
    <div class="message">
        Your votes have been successfully recorded.
    </div>
    <div>
        <a href="index.php">Return to Voting Page</a> <!-- Change to your voting page -->
    </div>
</body>
</html>