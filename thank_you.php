<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You for Voting</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #A7D7A0, #D0F0C0); /* Softer light green gradient */
            color: #2F4F2F; /* Dark greenish-gray for better contrast */
            font-family: 'Arial', sans-serif;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            max-width: 600px;
            background: #F0FFF0; /* Very light pastel green */
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            animation: fadeIn 1s ease-in-out;
        }
        h1 {
            font-weight: bold;
            color: #4C9A2A; /* Softer green */
        }
        p {
            font-size: 1.2em;
            margin-top: 10px;
            color: #355E3B; /* Dark olive green */
        }
        .btn-custom {
            padding: 12px 20px;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            border: none;
            border-radius: 8px;
            transition: all 0.3s ease-in-out;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .btn-home {
            background: #4C9A2A; /* Soft green button */
            color: white;
        }
        .btn-home:hover {
            background: #3B7E20;
            transform: translateY(-2px);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>🎉 Thank You for Voting! 🎉</h2>
        <p>Your vote has been successfully recorded.</p>
        <p>We appreciate your participation in the election.</p>
        <a href="vote1.php" class="btn btn-home btn-custom">Return to Home</a>
    </div>

</body>
</html>
