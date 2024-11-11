<!DOCTYPE html>
<html lang="en">
<?php 
session_start();
include('./db_connect.php');
ob_start();

// Fetch system settings and store them in the session
$system = $conn->query("SELECT * FROM system_settings")->fetch_array();
foreach ($system as $k => $v) {
    $_SESSION['system'][$k] = $v;
}

ob_end_flush();

// Redirect to home if the user is already logged in
if (isset($_SESSION['login_id'])) {
    header("location:index.php?page=home");
}
?>

<?php include 'header.php'; ?>
<link rel="stylesheet" href="Css/login.css">

<head>
</head>

<body>
  <div class="container">
    <div class="login-container">
      <div class="login-box">
        
        <!-- Login Side -->
        <div class="login-side">
          <div class="login-logo">
            <img src="images/feslogo.png" alt="Logo">
            <div class="logo-name">
              <h3><?php echo $_SESSION['system']['name']; ?></h3>
            </div>
          </div>

          <div class="account-login">
            <h1>Login to Your Account</h1>
          </div>

          <div class="card2">
            <div class="card-body">
              <form action="" id="login-form">
                <!-- Change input name to school_id for student/faculty or email for admin -->
                <div class="input-group">
                  <input type="text" class="form-control" name="email" required placeholder="Email or School ID">
                </div>

                <div class="input-group">
                  <input type="password" class="form-control" name="password" required placeholder="Password">
                </div>

                <div class="button">
                  <button type="submit" class="btn">Sign In</button>
                </div>

                <p style="text-align: center;">
                  <a href="homepage.php">Go back to site</a>
                </p>
              </form>
            </div>
            <!-- /.card-body -->
          </div>

          <!--
        </div>
           <div class="register-side">
          <div class="register-container">
            <div class="register-text">
              <h1>New Here?</h1>
              <p>Sign up and evaluate!</p>
            </div>
            <div class="row-button">
              <a href="register.php" class="regBtn">Sign Up</a>
            </div>
          </div>
        </div> -->
      

      </div>
    </div>
  </div>
  <!-- /.login-box -->

  <script>
    $(document).ready(function() {
      $('#login-form').submit(function(e) {
        e.preventDefault();
        start_load();

        if ($(this).find('.alert-danger').length > 0) {
          $(this).find('.alert-danger').remove();
        }

        $.ajax({
          url: 'ajax.php?action=login',
          method: 'POST',
          data: $(this).serialize(), // Send the form data
          error: function(err) {
            console.log(err);
            end_load();
          },
          success: function(resp) {
            if (resp == 1) {
              location.href = 'index.php?page=home'; // Redirect to home page
            } else {
              $('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>');
              end_load();
            }
          }
        });
      });
    });
  </script>

  <?php include 'footer.php'; ?>
</body>
</html>
