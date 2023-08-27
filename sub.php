<?php
session_start();
if (!isset($_SESSION['id'])) {
    // Redirect to the login page if the user is not authenticated
    header("Location: login.php");
    exit();
}

// Step 1: Create the MySQL database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['id'];
$subscription_query = "SELECT payment_status, e_date FROM subscription WHERE id = '$user_id' ORDER BY e_date DESC LIMIT 1";
$subscription_result = mysqli_query($conn, $subscription_query);
// Check if the user has a paid subscription
if ($subscription_result && mysqli_num_rows($subscription_result) > 0) {
    $subscription_info = mysqli_fetch_assoc($subscription_result);
    $payment_status = $subscription_info['payment_status'];
    $expiry = $subscription_info['e_date'];

    $current_date = new DateTime();
    $expiry_obj = new DateTime($expiry);

    if ($payment_status === 'paid' && $current_date < $expiry_obj) {
        
        // Redirect to the dashboard since the user has paid and subscription is active
        header("Location: dboard.php?id=$user_id");
        exit();
    }
}
// Fetch user's data from the registration table
$expiry_date = date('Y-m-d', strtotime('+1 month'));
$sql = "SELECT Name, BankInfo FROM registration WHERE id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_name = $row['Name'];
    $BankInfo = $row['BankInfo'];
} else {
    // Handle the case where user data is not found
    $user_name = '';
    $BankInfo = '';
}

$payment_amount = 0; // Default payment amount
$selected_plan = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $selected_plan = $_POST['subscription_plan'];

    if ($selected_plan === "Starter") {
        $payment_amount = 19;
    } elseif ($selected_plan === "Advance") {
        $payment_amount = 39;
    } elseif ($selected_plan === "Premium") {
        $payment_amount = 79;
    } else {
        // Handle any default or error case here
    }
    $delete_previous_query = "DELETE FROM subscription WHERE id = '$user_id' AND payment_status = 'paid' AND e_date < '$expiry'";
        mysqli_query($conn, $delete_previous_query);
    
    $subscription_query = "INSERT INTO subscription (id, s_date, e_date, plan, payment_status, reference, timestamp)
                           VALUES ('$user_id', NOW(), '$expiry_date', '$selected_plan', 'Pending', 'Reference123', NOW())";

    if ($conn->query($subscription_query) === TRUE) {
        // Subscription details inserted successfully
        header("Location: login.php?id=$user_id");
        exit();
    } else {
        // Error inserting subscription details
        echo "Error: " . $subscription_query . "<br>" . $conn->error;
    }
}

?>



    <!doctype html>
    <html lang="en">
      <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title> Subscription</title>
        <link href ="sub.css" rel="stylesheet" />
       
      </head>
      <body>
      <form action="sub.php" method="post">
        <div class="container p-0">
            <div class="card px-4">
                <p class="h8 py-3">Payment Details</p>
                <div class="row gx-3">
                    <div class="col-12">
                        <div class="d-flex flex-column">
                            <p class="text mb-1">Person Name</p>
                            <input class="form-control mb-3" type="text" placeholder="Name" value="<?php echo $user_name; ?>">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex flex-column">
                            <p class="text mb-1">Card Number</p>
                            <input class="form-control mb-3" type="text" placeholder="1234 5678 435678" value = "<?php echo $BankInfo; ?>">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex flex-column">
                            <p class="text mb-1">Expiry</p>
                            <input class="form-control mb-3" type="text" placeholder="MM/YYYY" value="<?php echo  $expiry_date; ?>" readonly>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex flex-column">
                            <p class="text mb-1">Plan</p>
                            <select class="form-control mb-3" name="subscription_plan">>
                                <option value="Starter" data-amount="19">Starter</option>
                                <option value="Advance" data-amount="39">Advance</option>
                                <option value="Premium" data-amount="79">Premium</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
    <div class="d-flex justify-content-center">
        <button type="submit" class="btn btn-primary">
            <span class="ps-3">Pay $<span id="payment-amount"></span></span>
            <span class="fas fa-arrow-right"></span>
        </button>
    </div>
</div>

                </div>
            </div>
        </div>
</form>
      
      </body>
    </html>