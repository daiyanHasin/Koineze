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
                        echo "</div>";
                    }
                } else {
                    echo "No records found for the provided user ID.";
                }  
            // Close the database connection
            //$conn->close();
            ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
    <title><?php  echo htmlspecialchars($name); ?></title>
    <link rel="stylesheet" href="dboard.css">
    <link rel="stylesheet" href="filter.css">
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
                <h4> <?php  echo htmlspecialchars($name); ?></h4>
            </div>

            <div class="side-menu">
                <ul>
                    <li>
                       <a href="" class="active">
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
                       <a href="expense.php?id=<?php echo $user_id; ?>">
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
                
                <div class="header-menu">

                <a href="notification.php?id=<?php echo $user_id; ?>" class="notify-icon">
                      <span class="las la-envelope"></span>
                       <span class="notify">4</span>
                </a>
                    
      
                    <div>
                    
                    </div>
                    
                    <div class="user">
                        <div><?php  echo htmlspecialchars($name); ?></div>
                        <div class="bg-img" style="background-image: url(<?php echo $img; ?>)"></div>
                        
                        <span class="las la-power-off"></span>
                        <span><a href="logout.php">Logout</a></span>
                        
                    </div>
                </div>
            </div>
        </header>
        
        
        <main>


            <div class="page-header">
                <h1>Dashboard</h1>
                <small>Home / Dashboard</small>
            </div>
            
            <div class="page-content">
            
                <div class="analytics">

                

                <?php
$sql = "SELECT SUM(amount) AS total_amount FROM transaction WHERE id = $user_id AND type = 'earnings'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_amount = $row['total_amount'];
} else {
      $total_amount = 0;
  // Set a default value if no transactions found
}

?>


                    <div class="card">
                        <div class="card-head">
                            <h2><?php echo htmlspecialchars($total_amount); ?></h2>
                            <span class="las la-user-friends"></span>
                        </div>
                        <div class="card-progress">
                            <small>Balance</small>
                            <div class="card-indicator">
                                <div class="indicator one" style="width: 60%"></div>
                            </div>
                        </div>
                    </div>

                    <?php
$sql = "SELECT SUM(amount) AS total_amount FROM transaction WHERE id = $user_id AND cid = 1001 group by id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_amount = $row['total_amount'];
} else {
    $total_amount = 0; // Set a default value if no transactions found
}

?>


                    <div class="card">
                        <div class="card-head">
                            
                            <h2><?php echo htmlspecialchars($total_amount); ?></h2>
                            <span class="las la-shopping-cart"></span>
                        </div>
                        <div class="card-progress">
                            <small>Food & Clothes</small>
                            <div class="card-indicator">
                            
                                <div class="indicator three" style="width:60%"></div>
                            </div>
                        </div>
                    </div>


                    <?php
$sql = "SELECT SUM(amount) AS total_amount FROM transaction WHERE id = $user_id AND cid = 1002 group by id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_amount = $row['total_amount'];
} else {
    $total_amount = 0; // Set a default value if no transactions found
}

?>


                    <div class="card">
                        <div class="card-head">
                            <h2><?php echo htmlspecialchars($total_amount); ?></h2>
                            <span class="las la-shopping-cart"></span>
                        </div>
                        <div class="card-progress">
                            <small>Fees & Bills</small>
                            <div class="card-indicator">
                            
                                <div class="indicator three" style="width:40%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <?php
$sql = "SELECT SUM(amount) AS total_amount FROM transaction WHERE id = $user_id AND cid = 1003 group by id = $user_id  ";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_amount = $row['total_amount'];
} else {
    $total_amount = 0; // Set a default value if no transactions found
}

?>
                    <div class="card">
                        <div class="card-head">
                            <h2><?php echo htmlspecialchars($total_amount); ?></h2>
                            <span class="las la-envelope"></span>
                        </div>
                        <div class="card-progress">
                            <small>Others</small>
                            <div class="card-indicator">
                                <div class="indicator four" style="width: 90%"></div>
                            </div>
                        </div>
                    </div>

                </div>

                <form class="filter-form" method="POST" action="filter.php">
        <div class="form-group">
            <label for="start_date">Start Date:</label>
            <input type="date" name="start_date" id="start_date" class="form-control">
        </div>
        <div class="form-group">
            <label for="end_date">End Date:</label>
            <input type="date" name="end_date" id="end_date" class="form-control">
        </div>
        <div class="form-group">
            <label for="selected_item">Item:</label>
            <select name="selected_item" id="selected_item" class="form-control">
                <option value="">Select Item</option>
                <option value="1001">Food & Clothes</option>
                <option value="1002">Fees & Bills</option>
                <option value="1003">Others</option>
            </select>
        </div>
        <button type="submit" class="btn-submit">Filter</button>
    </form>


<div>
        <table width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th><span class="las la-sort"></span>  Id</th>
                    <th><span class="las la-sort"></span> Type</th>
                    <th><span class="las la-sort"></span> ISSUED DATE</th>
                    <th><span class="las la-sort"></span> BALANCE</th>
                    <th><span class="las la-sort"></span> ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                <?php
              //  session_start();
 
                $sql = "SELECT t.*, r.Name, r.Email FROM transaction t
                JOIN registration r ON t.id = r.id
                WHERE r.id = $user_id ";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $row['tid'] . '</td>';
                        echo '<td>';
                       // echo '<div class="client">';
                       // echo '<div class="client-img bg-img" style="background-image: url(' . $row['img'] . ')"></div>';
                        echo '<div class="client-info">';
                        echo '<h4>' . $row['Name'] . '</h4>';
                        echo '<small>' . $row['Email'] . '</small>';
                        echo '</div></div></td>';
                        echo '<td>' . $row['type'] . '</td>';
                        echo '<td>' . $row['date'] . '</td>';
                        echo '<td>' . $row['amount'] . '</td>';
                        echo '<td>';
                        echo '<div class="actions">';
                        echo '<a class="UPdate-button" href="update_transaction_form.php?tid=' . $row['tid'] . '">Update</a>';
                        echo '<a class="DElete-button" href="delete_transaction.php?tid=' . $row['tid'] . '&uid=' . $user_id . '">Delete</a>';
                        echo '</div></td></tr>';
                    }
                } else {
                    echo '<tr><td colspan="6">No transactions found.</td></tr>';
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>




</body>
</html>



