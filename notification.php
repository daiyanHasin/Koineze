<?php
session_start();
if (!isset($_SESSION['id'])) {
    // Redirect to the login page if the user is not authenticated
    header("Location: login.php");
    exit();
}

$user_id = null;
// Retrieve the user ID from the URL query parameter
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}// Initialize variables for user data
$name = $img = $mail = $phn = $dob = "";

// Prepare and execute the SQL statement
$stmt = $conn->prepare("SELECT * FROM registration WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $name = $row["Name"];
        $img = $row["img"];
        $mail = $row["Email"];

    }
} else {
    echo "No records found for the provided user ID.";
}

$sql = "SELECT id, purpose, SUM(amount) AS total_amount FROM transaction WHERE id = $user_id GROUP BY id, purpose";
$result = $conn->query($sql);


if (!$result) {
    die("Query failed: " . $conn->error);
}

$expenseData = array();
while ($row = $result->fetch_assoc()) {
    $expenseData[] = $row;
}

$sqlEarnings = "SELECT SUM(amount) AS total_earnings FROM transaction WHERE id = ? AND type = 'earnings'";
$stmtEarnings = $conn->prepare($sqlEarnings);
$stmtEarnings->bind_param("i", $user_id);
$stmtEarnings->execute();
$resultEarnings = $stmtEarnings->get_result();
$rowEarnings = $resultEarnings->fetch_assoc();
$totalEarnings = $rowEarnings['total_earnings'];

// Retrieve total expenses for the user
$sqlExpenses = "SELECT SUM(amount) AS total_expenses FROM transaction WHERE id = ? AND type = 'expense'";
$stmtExpenses = $conn->prepare($sqlExpenses);
$stmtExpenses->bind_param("i", $user_id);
$stmtExpenses->execute();
$resultExpenses = $stmtExpenses->get_result();
$rowExpenses = $resultExpenses->fetch_assoc();
$totalExpenses = $rowExpenses['total_expenses'];

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/line-awesome@1.3.0/dist/css/line-awesome.min.css">
    <title>Notifications</title>
    <style>
        /* Your custom styles for notifications page */
        body {
            font-family: Arial, sans-serif;
        }
        
        .notification {
            padding: 10px;
            border-bottom: 1px solid #ccc;
            display: flex;
            align-items: center;
        }

        .notification-icon {
            font-size: 24px;
            margin-right: 10px;
        }

        .notification-text {
            flex: 1;
            color: #ff6347; /* Red color for warning */
            font-weight: bold;
        }

        /* Additional styles for warning notification */
        .warning-bg {
            background-color: #ffeeee; /* Light pink background color */
            border: 1px solid #ffadad; /* Light red border */
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1>Notifications</h1>

    <?php
    
    if ($totalExpenses > $totalEarnings) {
        echo '<div class="notification">';
        echo '<span class="notification-icon las la-exclamation-circle"></span>';
        echo '<div class="notification-text">';
        echo 'Warning: Total Expense is higher than Total Earnings';
        echo '</div>';
        echo '</div>';
    }
    
    ?>

    <!-- Add more notification divs as needed -->
</body>
</html>