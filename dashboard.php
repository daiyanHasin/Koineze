<?php
session_start();
// Step 1: Create the MySQL database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

// Handle database connection errors gracefully
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . htmlspecialchars($conn->connect_error));
}

// Step 2: Start the session


// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    // User is not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

// Retrieve user information from the database based on the user's session
$user_id = $_SESSION['id'];
$stmt = $conn->prepare("SELECT * FROM registration WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    // Fetch the user's data
    $row = $result->fetch_assoc();
    $userName = htmlspecialchars($row['Name']);
    $userEmail = htmlspecialchars($row['Email']);
    // Additional data retrieval from the database goes here
    
    // Example data population based on the retrieved data
    $profileImageURL = "img/default_profile.jpg";
    $balance = 0;
    $expenses = 0;
    $cash = 0;
    $clientRecords = array();
    // ...

    // Include the dashboard HTML structure with PHP data population
    include "dboard.html";
} else {
    // User not found, handle the error appropriately
    echo "User not found";
}

// Close resources
$stmt->close();
$conn->close();
?>
