<?php

session_start();
if (!isset($_SESSION['id'])) {
    // Redirect to the login page if the user is not authenticated
    header("Location: login.php");
    exit();
}
$user_id = null;
// Retrieve the user ID from the URL query parameter
if (isset($_GET['id'])) 
    $user_id = $_GET['id'];
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['update'])) {
    $tid = $_POST['tid'];
    $type = $_POST['type'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    
    // Update the transaction in the database
    $sql = "UPDATE transaction SET type = '$type', amount = '$amount', date = '$date' WHERE tid = $tid";
    
    if ($conn->query($sql) === TRUE) {
        echo "Transaction updated successfully.";
    } else {
        echo "Error updating transaction: " . $conn->error;
    }
}

$conn->close();
?>