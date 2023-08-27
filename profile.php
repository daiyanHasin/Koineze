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

// Initialize variables for user data
$name = $img = $mail = $phn = $dob = "";

// Prepare and execute the SQL statement
$stmt = $conn->prepare("SELECT * FROM registration WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $name = $row["Name"];
        $img = $row["img"];
        $mail = $row["Email"];
        $phn = $row["phoneno"];
        $dob = $row["date"];
        $bi =  $row["BankInfo"];
    }
} else {
    echo "No records found for the provided user ID.";
}
// Close the prepared statement
$stmt->close();
$stmt = $conn->prepare("SELECT * FROM subscription WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $ps = $row["payment_status"];
    }
} else {
    echo "No records found for the provided user ID.";
}


if (isset($_POST['Update'])) {
    // ... (Database connection code)

    // Retrieve user data from the form
    $newName = $_POST['Name'];
    $newEmail = $_POST['Email']; 
    $newPhone = $_POST['phoneno'];
    $newDOB = $_POST['date'];
 

    // Process image upload
    if ($_FILES['img']['error'] === UPLOAD_ERR_OK) {
        $newImageName = basename($_FILES['img']['name']);
        $newImage = 'images/' . $newImageName;
        move_uploaded_file($_FILES['img']['tmp_name'], $newImage);
    } else {
        // Handle image upload error if needed
        $newImage = $img; // Keep the existing image if no new image is uploaded
    }

    // Prepare and execute the update SQL statement
    $updateQuery = "UPDATE registration SET Name=?, phoneno=?, date=?,  img=? WHERE id=?";
   $stmt = $conn->prepare($updateQuery);

if ($stmt === false) {
    die("Prepare failed: " . $conn->error); // Add error handling
}

$stmt->bind_param("ssssi", $newName, $newPhone,  $newDOB, $newImage, $user_id);

    
    if ($stmt->execute()) {
        // Update successful
        echo "Profile updated successfully.";
    } else {
        // Update failed
        echo "Update failed: " . $stmt->error;
    }

    // Close the prepared statement
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
    <title>Profile</title>
    
    <link href="profile-style.css" rel="stylesheet" />
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
                       <a href="dboard.php?id=<?php echo $user_id; ?>" >
                            <span class="las la-home"></span>
                            <small>Dashboard</small>
                        </a>
                    </li>
                    <li>
                       <a href=""class="active">
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
                <div><?php echo htmlspecialchars($name);?></div>
                <div class="header-menu">
                    <div class="user">
                    <div class="bg-img" style="background-image: url(<?php echo $img; ?>)"></div>
                        <span class="las la-power-off"></span>
                        <span><a href="logout.php">Logout</a></span>
                    </div>
                </div>
            </div>
        </header>

        <div class="container rounded bg-white mt-5 mb-5">
            <div class="row">
            <div class="col-md-3 border-right">
                    <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                  
                   
                    <img class="rounded-circle mt-5" width="150px" src="<?php echo $img; ?>">
                     <!--   <div class="mt-5 text-center"><button class="btn btn-primary con-button" type="button">Save Profile</button></div> -->
                    </div>
                </div>
                <div class="col-md-5 border-right">
                    <div class="p-3 py-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h2 class="text-right">Profile Settings</h2>
                        </div>
                        <form method="post" action="profile.php?id=<?php echo $user_id; ?>" enctype="multipart/form-data">
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="labels">Name</label>
                                <input type="text" class="form-control" name="Name" value="<?php echo htmlspecialchars($name); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="labels">Email</label>
                                <input type="text" class="form-control" name="Email" value="<?php echo htmlspecialchars($mail); ?>">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label class="labels">Mobile Number</label>
                                <input type="text" class="form-control" name="phoneno" value="<?php echo htmlspecialchars($phn); ?>">
                            </div>
                        </div>
                        <div class="row-mt-3">
                            <div class="col-md-12">
                                <label class="labels">Date Of Birth</label>
                                <input type="text" class="form-control" name="date" value="<?php echo htmlspecialchars($dob); ?>">
                            </div>
                            <div class="col-md-12">
                                <label class="labels">Bankinfo</label>
                                <input type="text" class="form-control" placeholder="" value="<?php echo htmlspecialchars($bi); ?>">
                            </div>
                            <div class="col-md-12">
                                <label class="labels">Payment Stutus</label>
                                <input type="text" class="form-control" placeholder="" value="<?php echo htmlspecialchars($ps); ?>">
    
                            <div class="col-md-12">
                                <label class="labels">Image</label>
                                <input type="file" class="form-control" name="img" placeholder="Link" value="">
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="button-container">
                    <button class="update-button" type="submit" name= "Update">Update</button>
                    <button><a href="delete_account.php?id=<?php echo $user_id; ?>" class="delete-button">Delete Account</a></button>
                </div>
                </from>
            </div>
</div>
        </div>
        </div>    


</body>
</html>
 




