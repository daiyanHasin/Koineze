<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

$categoryMapping = [
    "Salary" => 1004,
    "Investments" => 1005,
    "Others" => 1003
];
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


// Handle the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Other form fields
    $purpose = $_POST['Purpose'];
    $amount = $_POST['Amount'];
    $category = $_POST['catagory'];

    // Check if an image was uploaded
    if (isset($_FILES["ReceiptImage"])) {
        // Image upload
        $targetDirectory = "images/"; // Specify the directory where you want to store the images
        $targetFileName = basename($_FILES["ReceiptImage"]["name"]);
        $targetFilePath = $targetDirectory . $targetFileName;

        if (move_uploaded_file($_FILES["ReceiptImage"]["tmp_name"], $targetFilePath)) {
            // Image uploaded successfully
        } else {
            echo "Error uploading image.";
        }
    } else {
        $targetFilePath = null; // No image uploaded
    }

    // Now insert the data into the database
    $category_id = $categoryMapping[$category];
    $stmt = $conn->prepare("INSERT INTO transaction (id, cid, type, amount, purpose, date, receipt)
                            VALUES (?, ?, 'earnings', ?, ?, NOW(), ?)");
     if (!$stmt) {
         die("Prepared statement error: " . $conn->error);
          }
    $stmt->bind_param("iiiss", $user_id, $category_id, $amount, $purpose, $targetFilePath);

    if ($stmt->execute()) {
        // Successful insertion
        echo "Data inserted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

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
    <title>Add Cash
    </title>
    <link rel="stylesheet" href="add-expense.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <style>
        /* Your custom CSS styles */
        .custom-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .custom-button:hover {
            background-color: #0056b3;
        }
        .cbutton-container {
            margin-top: 20px;
        }
    </style>
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
                       <a href="dboard.php?id=<?php echo $user_id; ?>" >
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
                       <a href="" class="active">
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
        
        
        
    <div>
        <main>
            <form method="post" action="add.php?id=<?php echo $user_id; ?>" enctype="multipart/form-data">
                <div class="container rounded bg-white mt-5 mb-5">
                    <h2 class="text-center">Add Money</h2>
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Purpose section -->
                            <div class="form-group">
                                <label for="Purpose">Purpose</label>
                                <input type="text" id="Purpose" class="form-control" name="Purpose" placeholder="Enter purpose">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Amount section -->
                            <div class="form-group">
                                <label for="Amount">Amount</label>
                                <input type="number" id="Amount" class="form-control" name="Amount" placeholder="Enter amount">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Category section -->
                            <div class="form-group">
                                <label for="Category">Category</label>
                                <select id="Category" class="form-control" name="catagory">
                                    <option value="Salary">Salary</option>
                                    <option value="Investments">Investments</option>
                                    <option value="Others">Others</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Receipt Image section -->
                            <div class="form-group">
                                <label for="ReceiptImage">Receipt Image</label>
                                <input type="file" id="ReceiptImage" class="form-control" name="ReceiptImage" accept="image/*">
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
            <div class="text-center cbutton-container">
                <form method="get" action="receipts.php">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($user_id); ?>">
                    <button class="custom-button" type="submit">Show Receipts</button>
                </form>
            </div>
        </main>
    </div>
    </div>
</body>
</html>
