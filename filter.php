<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
    <title><?php  echo htmlspecialchars($name); ?></title>
    <style>
        .filtered-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.filtered-table th {
    background-color: #007bff;
    color: white;
    padding: 10px;
    text-align: left;
}

.filtered-table td {
    border: 1px solid #ccc;
    padding: 10px;
}

.filtered-table tr:nth-child(even) {
    background-color: #f2f2f2;
}

.no-results {
    text-align: center;
    font-style: italic;
    color: #888;
    margin-top: 20px;
}
    </style>
</head>
<body>
<?php
session_start();
if (!isset($_SESSION['id'])) {
    // Redirect to the login page if the user is not authenticated
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['id']; // Get the user ID from the session

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle date filtering
$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';

// Handle item filtering
$selected_item = isset($_POST['selected_item']) ? $_POST['selected_item'] : '';

// Build the SQL query
$sql = "SELECT * FROM transaction WHERE id = $user_id";

if (!empty($start_date) && !empty($end_date)) {
    $sql .= " AND date BETWEEN '$start_date' AND '$end_date'";
}

if (!empty($selected_item)) {
    $sql .= " AND cid = $selected_item";
}

$result = $conn->query($sql);

// Fetch and display filtered transactions
if ($result->num_rows > 0) {
    echo '<table class="filtered-table">';
    echo '<tr><th>TID</th><th>Transaction Date</th><th>Amount</th><th>Purpose</th><th>Type</th></tr>';
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row['tid'] . '</td>';
        echo '<td>' . $row['date'] . '</td>';
        echo '<td>' . $row['amount'] . '</td>';
        echo '<td>' . $row['purpose'] . '</td>';
        echo '<td>' . $row['type'] . '</td>';
        echo '</tr>';
    }
    echo '</table>';
} else {
    echo '<p class="no-results">No transactions found.</p>';
}


$conn->close();
?>
</body>
</html>