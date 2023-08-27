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
   // $tid = $_GET['tid'];
if (isset($_GET['tid'])) {
    $transaction_id = $_GET['tid'];

    // Delete the row from the transaction table
    $delete_query = "DELETE FROM transaction WHERE tid = $transaction_id";

    if ($conn->query($delete_query) === TRUE) {
        // Successful deletion
        $user_id = $_GET['uid'];
        header("Location: dboard.php?id=$user_id"); // Redirect back to the dashboard
        exit();
    } else {
        // Error in deletion
        echo "Error deleting record: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>