<?php
                session_start();

                $categoryMapping = [
                    "Food&Clothes" =>1001,
                    "Fees&Bills"=>1002,
                    "Others" => 1003
                ];
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
            
                // Prepare the SQL statement
                $stmt = $conn->prepare("SELECT * FROM registration WHERE id = ?");
                if (!$stmt) {
                    die("Prepared statement error: " . $conn->error);
                }
            
                // Bind the user ID parameter
                $stmt->bind_param("i", $user_id);
            
                // Execute the prepared statement
                if (!$stmt->execute()) {
                    die("Execution error: " . $stmt->error);
                }
            
                // Get the result
                $result = $stmt->get_result();
            
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div>";
                        $name  = $row["Name"];
                        $img  =  $row["img"];
                        // Add more fields here
                    }
                } else {
                    echo "No records found for the provided user ID.";
                }  

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $purpose = $_POST['expensePurpose'];
                    $amount = $_POST['expenseAmount'];
                    $category = $_POST['expenseCategory'];
                    
                    $category_id = $categoryMapping[$category];
                    // Use prepared statements to prevent SQL injection
                    $stmt = $conn->prepare("INSERT INTO transaction (id, cid, type, amount, purpose, date)
                                            VALUES (?, ?, 'expense', ?, ?, NOW())");
                    $stmt->bind_param("iids", $user_id, $category_id, $amount, $purpose);
                
                    if ($stmt->execute()) {
                        // Successful insertion, you can choose to redirect or display a success message
                        echo "Data inserted successfully!";
                    } else {
                        echo "Error: " . $stmt->error;
                    }
                
                    // Close the statement
                    $stmt->close();
                }
            // Close the database connection
            $conn->close();
            ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
    <title>Add Expense</title>
    <link rel="stylesheet" href="add-expense.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
</head>
<body>
   <input type="checkbox" id="menu-toggle">
    <div class="sidebar">
        <div class="side-header">
            <h3>K<span>oineze</span></h3>
        </div>
        
        <div class="side-content">
            <div class="profile">
                <div class="profile-img bg-img" style="background-image: url(<?php echo $img; ?>)"></div>
                <h4><?php echo htmlspecialchars($name); ?></h4>
            </div>

            <div class="side-menu">
                <ul>
                    <li>
                       <a href="dboard.php?id=<?php echo $user_id; ?>">
                            <span class="las la-home"></span>
                            <small>Dashboard</small>
                        </a>
                    </li>
                    <li>
                       <a href="profile.php?id=<?php echo $user_id; ?>">
                            <span class="las la-user-alt"></span>
                            <small>Profile</small>
                        </a>
                    </li>
                    <li>
                       <a href="report.php?id=<?php echo $user_id; ?>">
                            <span class="las la-envelope"></span>
                            <small>Report</small>
                        </a>
                    </li>
                    <li>
                       <a href="" class="active">
                            <span class="las la-clipboard-list"></span>
                            <small>Add Expense</small>
                        </a>
                    </li>
                    <li>
                       <a href="add.php?id=<?php echo $user_id; ?>">
                            <span class="las la-shopping-cart"></span>
                            <small>Add Cash</small>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="main-content">
        
    <header>
            <div class="header-content">
                <label for="menu-toggle">
                    <span class="las la-bars"></span>
                </label>
                <div><?php echo htmlspecialchars($name);?></div>
                <div class="header-menu">
                    <div class="user">
                    <div class="bg-img" style="background-image: url(<?php echo htmlspecialchars($img); ?>)"></div>
                        <span class="las la-power-off"></span>
                        <span><a href="logout.php">Logout</a></span>
                    </div>
                </div>
            </div>
        </header>
        
        <main>
        <form method="post" action="expense.php?id=<?php echo $user_id; ?>">
            <div class="container rounded bg-white mt-5 mb-5">
                <h2 class="text-center">Add Expense</h2>
                <div class="row">
                    <div class="col-md-12">
                        <!-- Purpose section -->
                        <div class="form-group">
                            <label for="expensePurpose">Purpose</label>
                            <input type="text" id="expensePurpose" name = "expensePurpose" class="form-control" placeholder="Enter purpose">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <!-- Amount section -->
                        <div class="form-group">
                            <label for="expenseAmount">Amount</label>
                            <input type="number" id="expenseAmount" name = "expenseAmount" class="form-control" placeholder="Enter amount">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Category section -->
                        <div class="form-group">
                            <label for="expenseCategory">Category</label>
                            <select id="expenseCategory" name = "expenseCategory" class="form-control">
                                <option value="Food&Clothes">Food & Clothes</option>
                                <option value="Fees&Bills">Fees & Bills</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <!-- Submit button -->
                        <button class="btn btn-primary con-button" type="submit">Submit</button>
                    </div>
                </div>
            </div>
            </form>
            </main>
            </div>
</body>
</html>