
<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Name = $_POST['Name'];
    $Email = $_POST['Email'];
    $Password = $_POST['Password'];
    $cPassword = $_POST['cPassword'];
    $phoneno = $_POST['phoneno'];
    $BankInfo = $_POST['BankInfo'];
    $date = $_POST['date'];

    // Basic input validation
    if (empty($Name) || empty($Email) || empty($Password) || empty($cPassword) || empty($phoneno) || empty($BankInfo) || empty($date)) {
        echo "All fields are required.";
    } elseif ($Password !== $cPassword) {
        header("Location: signup.php?error=1");
    } else {
        // Establish a database connection
        $conn = new mysqli('localhost', 'root', '', 'project');

        // Check for connection errors
        if ($conn->connect_error) {
            die('Connection Failed: ' . $conn->connect_error);
        } else {
            // Check if the email already exists in the database
            $stmt = $conn->prepare("SELECT Email FROM registration WHERE Email = ?");
            $stmt->bind_param("s", $Email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                // Email already exists, show an error message
                echo "This email is already registered.";
            } else {
                // Hash the password
                $hashedPassword = password_hash($Password, PASSWORD_DEFAULT);

                // Prepare and execute the INSERT query
                $insertStmt = $conn->prepare("INSERT INTO registration (Name, Email, Password, phoneno, BankInfo, date) VALUES (?, ?, ?, ?, ?, ?)");
                $insertStmt->bind_param("ssssis", $Name, $Email, $hashedPassword, $phoneno, $BankInfo, $date);

                // Execute the INSERT query
                if ($insertStmt->execute()) {
                    $_SESSION['id'] = $user_id;
                    header("Location: dboard.php?id=$user_id");
                    exit();
                } else {
                    echo "Error: " . $insertStmt->error;
                }

                // Close the INSERT statement
                $insertStmt->close();
            }

            // Close the SELECT statement and connection
            $stmt->close();
            $conn->close();
        }
    }
}
?>
