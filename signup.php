<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Signup - Bootstrap Demo</title>
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
          <div class="col-lg-7">
            <div class="px-6 pt-6">
              <h1 class="font-weight-bold py-3">KOINEZE</h1>
              <h4>Create an Account</h4>
              <form action="connect.php" method="post">
                <div class="form-now">
                  <div class="col-lg-7">
                    <input type="text" placeholder="Name" class="form-control my-3 p-4" id="Name" name="Name" required>
                  </div>
                </div>

                <div class="form-now">
                  <div class="col-lg-7">
                    <input type="email" placeholder="Email" class="form-control my-3 p-4" id="Email" name="Email" required>
                  </div>
                </div>

                <div class="form-now">
                  <div class="col-lg-7">
                    <input type="password" placeholder="Password" class="form-control my-3 p-4" id="Password" name="Password" required>
                  </div>
                </div>

                <div class="form-now">
                  <div class="col-lg-7">
                    <input type="password" placeholder="Confirm Password" class="form-control my-3 p-4" id="cPassword" name="cPassword" required>
                    <?php
                    // Display an alert message if login fails
                    
                    // Display an error message if it's present in the URL
                    if (isset($_GET['error']) && $_GET['error'] == 1) {
                        echo "<div class='alert alert-danger mt-3'>Login failed: Passwords do not match.";
                    }
                    ?>
                </div>
                  </div>
                </div>

                <div class="form-now">
                  <div class="col-lg-7">
                    <input type="text" placeholder="Phone Number" class="form-control my-3 p-4"  id="phoneno" name="phoneno" required >
                  </div>
                </div>

                <div class="form-now">
                  <div class="col-lg-7">
                    <input type="text" placeholder="BankInfo" class="form-control my-3 p-4" id="BankInfo" name="BankInfo" required>
                  </div>
                </div>

                <div class="form-now">
                  <div class="col-lg-7">
                    <input type="date" class="form-control my-3 p-4"  id="date" name="date" required required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                     title="Must contain at least one number, one uppercase letter, one lowercase letter, and at least 8 or more characters">
                  </div>
                </div>

                <div class="form-now">
                  <div class="col-lg-7">
                    <button type="submit" class="btn1 mt-3 mb-5">Sign Up</button>
                  </div>
                </div>

                <a href="login.php">Already have an account? Log In</a>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>


  </body>
</html>
