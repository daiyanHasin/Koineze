

<?php
session_start();
// Step 1: Create the MySQL database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 2: Retrieve the user's email and password from the submitted form
$email = $_POST['email'];
$password = $_POST['password'];

// Step 3: Use prepared statements to check if the email exists
$stmt = $conn->prepare("SELECT id,Name,Password FROM registration WHERE Email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    // Fetch the stored hashed password from the database
    $row = $result->fetch_assoc();
    $stored_hashed_password = $row['Password'];
    $user_id = $row['id'];
    $userName= $row['Name'];

    // Verify the provided password against the stored hashed password
    if (password_verify($password, $stored_hashed_password)) {
        $subscription_query = "SELECT payment_status FROM subscription WHERE id = $user_id ORDER BY end_date DESC LIMIT 1";
        $subscription_result = mysqli_query($conn, $subscription_query);

        if ($subscription_result && mysqli_num_rows($subscription_result) > 0) {
            $subscription_info = mysqli_fetch_assoc($subscription_result);
            $payment_status = $subscription_info['payment_status'];

            if ($payment_status === 'paid') {
                // User has paid, set session variables and redirect to dashboard
                $_SESSION['id'] = $user_id;
                $_SESSION['Name'] = $userName;
                header("Location: dboard.php?id=$user_id");
                exit();
            } else {
                // User hasn't paid, redirect to subscription page
                $_SESSION['id'] = $user_id;
                header("Location: sub.php?id=$user_id");
                exit();
            }
        } else {
            // User doesn't have a subscription, redirect to subscription page
            $_SESSION['id'] = $user_id;
            header("Location: sub.php?id=$user_id");
            exit();
        }} else {
        // Handle incorrect password
 // Failed login, redirect back to the login page with an error message
 header("Location: login.php?error=1");
 exit();
    }
} else {
    // Handle non-existent email
    header("Location: login.php?error=1");
    exit();
}

$stmt->close();
$conn->close();
?>




