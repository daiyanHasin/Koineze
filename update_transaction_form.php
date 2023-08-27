<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Transaction</title>
     
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-top: 0;
            font-size: 24px;
            color: #333;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"],
        input[type="date"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 14px;
            color: #555;
        }

        button[type="submit"] {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        a {
            display: block;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
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
    $tid = $_GET['tid'];
    
    // Fetch the transaction details from the database
    $sql = "SELECT * FROM transaction WHERE tid = $tid";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        ?>
        
        <h2>Update Transaction</h2>
        
        <form method="post" action="process_update.php">
            <input type="hidden" name="tid" value="<?php echo $row['tid']; ?>">
            
            <label for="type">Type:</label>
            <input type="text" name="type" value="<?php echo $row['type']; ?>">
            
            <label for="amount">Amount:</label>
            <input type="text" name="amount" value="<?php echo $row['amount']; ?>">
            
            <label for="date">Date:</label>
            <input type="date" name="date" value="<?php echo $row['date']; ?>">
            
            <button type="submit" name="update">Update</button>
        </form>
    
        <?php
    } else {
        echo "Transaction not found.";
      /*  header("Location: dboard.php?id=$user_id");*/
    }
    
    $conn->close();
    ?>
</body>
</html>






