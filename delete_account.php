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
}


    $user_id = $_SESSION['id']; // Assuming you have the user's ID stored in the session

    $deleteTransactionQuery = "DELETE FROM transaction WHERE id=?";
    $stmt = $conn->prepare($deleteTransactionQuery);
    $stmt->bind_param("i", $user_id);
    
    if (!$stmt->execute()) {
        echo "Error deleting transactions: " . $stmt->error;
        exit();
    }
    
    $stmt->close();

    $deleteSubscriptionQuery = "DELETE FROM subscription WHERE id=?";
    $stmt = $conn->prepare($deleteSubscriptionQuery);
    $stmt->bind_param("i", $user_id);

    if (!$stmt->execute()) {
        echo "Error deleting subscriptions: " . $stmt->error;
        exit();
    }

    $stmt->close();

    $deleteUserQuery = "DELETE FROM registration WHERE id=?";
    $stmt = $conn->prepare($deleteUserQuery);
    $stmt->bind_param("i", $user_id);

    if (!$stmt->execute()) {
        echo "Error deleting user: " . $stmt->error;
        exit();
    }
    
    $stmt->close();

    // Deletion successful
    // Redirect to a confirmation page or login page
    header("Location: delete_confirmation.php");
    exit();


// Close the database connection
// ...

?>






