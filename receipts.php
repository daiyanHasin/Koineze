<?php
session_start();

// Validate user authentication
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$user_id = null;
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

$receipts = [];
$sql = "SELECT receipt FROM transaction WHERE id = ? AND receipt IS NOT NULL";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepared statement error: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $receipts[] = $row['receipt'];
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Receipts</title>
  
</head>
<body>
    <main>
        <h2>Your Receipts</h2>
        <div class="receipt-list">
            <?php foreach ($receipts as $receiptPath) : ?>
                <div class="receipt-item">
                    <img src="<?php echo htmlspecialchars($receiptPath); ?>" alt="Receipt">
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>
