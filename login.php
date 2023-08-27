<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="login.css" rel="stylesheet" />
</head>
<body>
  <section class="form my-4 mx-5">
    <div class="container">
      <div class="row no-gutters">
        <div class="col-lg-5">
          <img src="coins.jpg" class="img-fluid" alt="">
        </div>
        <div class="col-lg-7 px-6 pt-6">
          <h1 class="font-weight-bold py-3">KOINEZE</h1>
          <h4>Log Into Your Account</h4>
    <form action="login-connection.php" method="post">

      <div class="form-now">
        <div class="col-lg-7">
            <input type="email" name="email" placeholder="email" class="form-control my-3 p-4">
            <!-- Display error message for email -->
            <?php
            if (isset($_SESSION['email_error'])) {
                echo "<p style='color: red;'>".$_SESSION['email_error']."</p>";
                unset($_SESSION['email_error']); // Clear the error message
            }
            ?>
        </div>
    </div>

    <div class="form-now">
        <div class="col-lg-7">
            <input type="password" name="password" placeholder="*******" class="form-control my-3 p-4">
            <!-- Display error message for password -->
            <?php
            // Display an alert message if login fails
            
            // Display an error message if it's present in the URL
            if (isset($_GET['error']) && $_GET['error'] == 1) {
                echo "<div class='alert alert-danger mt-3'>Login failed: Incorrect email or password.";
            }
            ?>
        </div>
    </div>

    <div class="form-now">
        <div class="col-lg-7">
            <button type="submit" class="btn1 mt-3 mb-5"> Log IN </button>
        </div>
    </div>
</form>
       <!-- <input type="email" name="email" placeholder="Email">
        <input type="password" name="password" placeholder="Password">
        <button type="submit">Login</button>-->
        
       <p>New Here? <a href="signup.php">Register Now</a></p>
   
</body>
</html>
