<?php
// Database connection
$servername = "127.0.0.1";
$username = "u878574291_ccs1";
$password = "CCSPseudocode01";
$database = "u878574291_ccs";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize query and result variables
$result = null;

if (isset($_GET['query']) && !empty($_GET['query'])) {
    // Sanitize input
    $searchQuery = "%" . $conn->real_escape_string($_GET['query']) . "%";

    $sql = "SELECT 
            s.student_id, 
            s.last_name, 
            s.first_name, 
            s.middle_initial, 
            s.suffix, 
            s.major, 
            s.year_level, 
            p.lates, 
            p.absents, 
            p.monthly_dues, 
            p.department_shirt, 
            p.paid,
            p.total
        FROM 
            students AS s
        LEFT JOIN 
            penalty_transaction AS p 
        ON 
            s.student_id = p.student_id
        WHERE 
            s.student_id LIKE ? 
            OR s.middle_initial LIKE ? 
            OR s.first_name LIKE ? 
            OR s.last_name LIKE ? 
            OR s.major LIKE ? 
            OR s.year_level LIKE ? 
            OR s.suffix LIKE ?
        ORDER BY 
            s.last_name ASC";


    // SQL query with placeholders
    $sql = "SELECT * FROM students
    WHERE student_id LIKE ? 
    OR middle_initial LIKE ? 
    OR first_name LIKE ? 
    OR last_name LIKE ? 
    OR major LIKE ? 
    OR year_level LIKE ? 
    OR suffix LIKE ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
die("Prepare failed: " . $conn->error);
}

// Correct number of bind variables
$stmt->bind_param("sssssss", $searchQuery, $searchQuery, $searchQuery, $searchQuery, $searchQuery, $searchQuery, $searchQuery);

// Execute query
$stmt->execute();
$result = $stmt->get_result();
 
} else {
    // Default query (when no search term is provided)
    $sql = "SELECT * FROM penalty_transaction";
    $result = $conn->query($sql);

    if (!$result) {
        die("Query failed: " . $conn->error); // Output SQL error for debugging
    }
}

// Close connection
$conn->close();
?>





<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Search</title>
        <style>

* {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            outline: none;
            border: whitesmoke;
            text-decoration: none;
        }

    

        table {
            width: 100%;
            border-collapse: collapse; /* Merge borders */
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background-color: darkgreen; /* Header background color */
            color: white;
            font-family: 'Arial', sans-serif;
            height:100px;
        }


        .header h1 {
            margin-left: 110px; /* Remove default margin */
            margin-bottom: px;
            font-size: 24px; /* Header title font size */
            font-family: 'Georgia', serif; /* Use a serif font for headings */
            font-weight: bold; /* Bold headings */
            text-shadow: 3px 3px 5px rgba(0, 0, 0, 0.3);
        }
        #draggableImage {
            width: 39px; /* Set the width of the image */
            height: 38px;
            position: absolute; /* Allow free positioning */
            top: 20px; /* Position from the top of the header */
            left: 445px; /* Position from the left of the header */
            border-radius: 50px;
        }
        .header .college-subtitle {
            font-size: 21px;
            font-family: 'Georgia', serif;
            color: #b8e994; /* Light green color for the subtitle */
            margin-top: 5px;
            font-size: 15px;
            margin-left: 190px;
        }
        .header .title-container {
            display: flex;
            flex-direction: column;
            margin-left: 10px;
        }


        .header input {
            font-family: 'Georgia', serif;
            padding: 5px 3px;
            font-size: 15px;
            border: 2px solid #0B890B; /* Border color to match DLSJBC's green */
            border-radius: 10px;
            outline: none;
            width: 200px;
            transition: padding-left 0.5s ease;
            margin-right: 5px;
        }

        /* Style the search input placeholder */
        .header input[type="text"]::placeholder {
            padding: 5px 3px;
            font-family: 'Georgia', serif;
            font-size: 15px;
            transition: transform 0.5s ease;
            color: #888;
        }

        /* Shift placeholder text on focus */
        .header input[type="text"]:focus::placeholder {
            padding: 5px 3px;
            font-size: 15px;
            font-family: 'Georgia', serif;
            transform: translateX(-100px);
            color: transparent; /* Optional: hides placeholder text after moving */
        }

        .header button {
            padding: 5px 10px; /* Button padding */
            background: whitesmoke; /* Button background color */
            color: black; /* Button text color */
            border: none; /* Remove border */
            border-radius: 5px; /* Rounded corners */
            cursor: pointer; /* Change cursor to pointer */
        }

        .container {
            position: relative;
            padding-bottom: 80px; /* Add padding for lower box */
        }

        .content h2 {
            font-size: 18px;
            margin-top: 40px;
            margin-left: 80px; 
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 16px;
            font-weight: bold;
            font-family: Georgia, 'Times New Roman', Times, serif;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            font-size: 17px;
            font-family: Georgia, 'Times New Roman', Times, serif;
        }

        .modal {
            display: none;
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        .modal-content {
            margin: 15% auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            width: 300px;
        }

        .modal-content h2 {
            font-size: 20px;
            gap: 15px;
            margin-left: 5px;
            margin-bottom: 20px;
            margin-top: 10px;
            font-weight: bold;
            font-family: Arial, Helvetica, sans-serif;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            color: white;
            background-color: #004d00; /* Dark green */
            text-decoration: none;
            text-align: center;
            margin-top: 20px;
            margin: 5px;
            margin-left: 5px;
            border-radius: 300px;
        }

        .untouchable {
            background-color: lightslategrey;
            cursor: not-allowed;
            border-radius: 14px;
            font-weight: bold;
        }

        .table-container {
            max-height: 510px; /* Set your desired height */
            margin-top: 5px;
            margin-left: 80px;
            margin-right: 80px;
            align-items: center;
            overflow-y: auto;  /* Enable vertical scrolling */
            border: 2px solid #ccc; /* Optional: add a border for clarity */
            border-radius: 5px; /* Optional: round the corners */
            box-shadow: 10px 10px 20px rgba(0, 0, 0, 0.1);   
        }
        table tr:hover {
    background-color: lightgray; /* Change to light gray on hover */
}
        .table-container::-webkit-scrollbar {
            width: 11px; /* Width of the scrollbar */
          
        }

        .table-container::-webkit-scrollbar-thumb {
            background-color: #888; /* Color of the scrollbar thumb */
            border-radius: 5px; /* Round the edges of the scrollbar thumb */
        }

        .table-container::-webkit-scrollbar-thumb:hover {
            background-color: #555; /* Color on hover */
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        td {
            font-size: 14px;
            font-family: Arial, Helvetica, sans-serif;
        }

        th {
    background-color: #f2f2f2; /* Background color for header */
    text-align: center;
    font-size: 15px;
    font-family: Arial, Helvetica, sans-serif;
    font-weight: bold;
    position: sticky; /* Makes the header sticky */
    top: 0; /* Position at the top of the scrolling area */
    z-index: 1; /* Ensure it is above other content */
}

        .logout-box {
            position: fixed;
            bottom: 10px;
            right: 20px;
            background-color: #f8f9fa; /* Light background for the logout box */
            padding: 15px;
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }

        .logout-btn {
            font-size: 12px;
            display: inline-block;
            padding: 10px;
            background-color: darkgreen; /* Blue background */
            color: white; /* White text */
            text-align: center;
            border-radius: 12px; /* Rounded corners */
            margin: 20px ; /* Margin for spacing */
            left: 1400px;
            position:fixed;
            top:666px;
            text-decoration: none; /* Remove underline */
            font-family: Arial, Helvetica, sans-serif;
            letter-spacing: 1px; 
            text-shadow: 3px 3px 5px rgba(0, 0, 0, 0.3);
        }
    
        .back-btn {
    font-size: 12px;
    display: inline-block;
    font-weight: bold;
    background-color: transparent; /* Blue background */
    color: white; /* White text */
    text-align: center;
    border-radius: 4px; /* Slightly rounded corners */
    margin: 20px; /* Margin for spacing */
    position: fixed;
    left: 0px;
    top: 0px;
    text-decoration: none; /* Remove underline */
    font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    letter-spacing: 2px; 
    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
}
.back-btn:hover {
    background-color: #005600; /* Darker green on hover */
    transform: scale(1.3); /* Slightly enlarge button on hover */
}

        </style>
    </head>
   <body>

   <div class="header">
    <div style="display: flex; align-items: center;">
        <img src="\form\images\Dls-jbc.jpg" id="draggableImage" alt="Image" />
    </div>
    <div class="title-container">
        
            <h1>De La Salle John Bosco College</h1>
            <p class="college-subtitle">College of Computer Studies</p>
        </div>
        
        <form action="search.php" method="GET" style="display: flex; align-items: center;">
        <input type="text" name="query" placeholder="Student ID, Name, Program and Year Level" required autocomplete="off"> 
        <button type="submit" class="btn">Search</button>
    </form>
</div>

<div class="container">
    <div class="content">
        <h2>Penalty Transactions</h2>
        <div class="table-container"> <!-- Added scrollable container -->
            <table>
                <tr>
                    <th>Student ID</th>
                    <th>Last Name</th>
                    <th>First Name/th>
                    <th>Middle Name</th>
                    <th>Suffix</th>
                    <th>Program </th>
                    <th>Year Level</th>
                    <th colspan="2">Penalty</th> <!-- Two columns under Penalty -->
                        <th colspan="2">Contribution</th> <!-- Two columns under Contribution -->
                        <th>Total</th>
                        <th>Balance</th>
                        <th>Actions</th>
                    </tr>
                    <tr>
                        <th colspan="7"></th>
                        <th>Late</th>
                        <th>Absent</th>
                        <th>Montlhy Dues</th>
                        <th>Department Shirt</th>
                        <th colspan="4"></th>
                    </tr>
                    </thead>
                <?php
               if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $balance = ($row['total'] ?? 0) - ($row['paid'] ?? 0);

                    echo "<tr>
                        <td>" . htmlspecialchars($row['student_id'] ?? '') . "</td>
                        <td>" . htmlspecialchars($row['last_name'] ?? '') . "</td>
                        <td>" . htmlspecialchars($row['first_name'] ?? '') . "</td>
                        <td>" . htmlspecialchars($row['middle_initial'] ?? '') . "</td>
                        <td>" . htmlspecialchars($row['suffix'] ?? '') . "</td>
                        <td>" . htmlspecialchars($row['major'] ?? '') . "</td>
                        <td>" . htmlspecialchars($row['year_level'] ?? '') . "</td>
                        <td>" . htmlspecialchars(number_format($row['lates'] ?? 0, 2)) . "</td>
                        <td>" . htmlspecialchars(number_format($row['absents'] ?? 0, 2)) . "</td>
                        <td>" . htmlspecialchars(number_format($row['monthly_dues'] ?? 0, 2)) . "</td>
                        <td>" . htmlspecialchars(number_format($row['department_shirt'] ?? 0, 2)) . "</td>
                        <td>" . htmlspecialchars(number_format($row['total'] ?? 0, 2)) . "</td>
                        <td>" . htmlspecialchars(number_format($balance, 2)) . "</td>
                        <td>
                      
            
                            <a href='view.php?id=" . htmlspecialchars($row['student_id']) . "' class='btn'>View</a>
                            <a href='delete.php?id=" . htmlspecialchars($row['student_id']) . "' class='btn' onclick='return confirm(\"Are you sure you want to delete this transaction?\");'>Delete</a>";

                    if ($balance > 0) {
                        echo "<button class='btn' onclick=\"document.getElementById('paymentModal" . htmlspecialchars($row['student_id']) . "').style.display='block'\">Pay</button>";
                    } else {
                        echo "<button class='btn untouchable' onclick=\"alert('Paid Already!')\" disabled>Paid</button>";
                    }

                    echo "</td>
                    </tr>";
  // Payment Modal
  echo "<div id='paymentModal" . htmlspecialchars($row['student_id']) . "' class='modal'>
  <div class='modal-content'>
      <span class='close' onclick=\"document.getElementById('paymentModal" . htmlspecialchars($row['student_id']) . "').style.display='none'\">&times;</span>
      <h2>Record Payment of: " . htmlspecialchars($row['first_name']. ' ' . $row['last_name']) . "</h2>";

  if ($balance == 0) {
      echo "<p>This transaction is fully paid. No further payments can be made.</p>";
  } else {
      // Calculate the remaining penalty
      $remainingPenalty = $row['penalty'] - $row['paid'];

      echo "<form action='admin_page.php' method='POST' onsubmit='return confirmPayment()'>
          <input type='hidden' name='transaction_id' value='" . htmlspecialchars($row['id']) . "'>
          <div class='form-group'>
              <label>Remaining Penalty Amount</label>
              <input type='text' value='" . htmlspecialchars(number_format($remainingPenalty, 2)) . "' disabled>
          </div>
          <div class='form-group'>
              <label for='paid'>Amount Paid</label>
              <input type='number' step='0.01' name='paid' id='paid" . htmlspecialchars($row['id']) . "' placeholder='Enter amount paid' required max='" . htmlspecialchars($remainingPenalty) . "'>
          </div>
          <div class='form-group'>
              <label for='date_of_payment'>Date of Payment</label>
              <input type='date' name='date_of_payment' required  >
          </div>
          <button type='submit' class='btn'>Record Payment</button>
          <button type='button' class='btn' onclick=\"document.getElementById('paymentModal" . htmlspecialchars($row['id']) . "').style.display='none'\">Cancel</button>
      </form>
      <script>
          function confirmPayment() {
              var remainingPenalty = " . json_encode($remainingPenalty) . ";
              var amountPaid = parseFloat(document.getElementById('paid" . htmlspecialchars($row['id']) . "').value);
              
              if (amountPaid > remainingPenalty) {
                  alert('Amount paid cannot exceed the remaining penalty of ' + remainingPenalty.toFixed(2));
                  return false;
              }
              return true;
          }
      </script>";
  }
           
                        echo "</div></div>";
                    }
                } else {
                    echo "<tr><td colspan='11'>No transactions found</td></tr>"; 
                }
                ?>
            </table>    
            </table>
            <a href="admin_page.php" class="back-btn">
    <svg width="24" height="24" viewBox="0 0 20 30" style=" vertical-align: middle;">
        <path fill="currentColor" d="M5 15 H22 M5 15 L13 10 M5 15 L13 20" stroke="currentColor" stroke-width="2"></path>
    </svg>
    Back
</a>
<a href="logout.php" class="btn logout-btn" onclick="return confirmLogout()">Logout</a>
        </div>
                
    </div>
    
    <script>
        // Confirmation dialog before recording payment
        function confirmPayment() {
            return confirm("Are you sure you want to record this payment?");
        }   

        // Close modal when clicking outside of it
        window.onclick = function(event) {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            });
        }
    </script>


    </body>
    </html>
