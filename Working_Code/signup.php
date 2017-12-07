<?php
  session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>NotifyMe! Sign Up</title>
    <link rel="stylesheet" type="text/css" href="default.css">
  </head>
  <body>
    <div class="homepage">
      <div class="form">
        <div class="title">
          Welcome!
        </div>
        <br>
        <?php
        	//$server = "localhost";
        	//$db = "notifyme_db";
        	//mysqli_connect($server,$username,$password,$db);
        	$conn = mysqli_connect("localhost","root","","notifyme_db");
        	//$conn  mysql_select_db("notifyme_db");
        	if( isset($_POST['email']) && isset($_POST['pass']) ){
        		$Email = mysqli_real_escape_string($conn,strip_tags($_POST['email']));
            $_SESSION["email"] = $Email;
        		$Password = mysqli_real_escape_string($conn,strip_tags($_POST['pass']));
            $_SESSION["pass"] = $Password;
        		$run_sqlCreateAccount = "INSERT INTO users (email, password) VALUES ('$Email', '$Password')";
        		if(mysqli_query($conn,$run_sqlCreateAccount)){
              echo "<script>window.location = \"userHomepage.php/\"; </script>";
        		}
        		else {
              ?>
              <div class="failure">
                Sign up failed!  That email address already has an account.
                Please enter a new email address and try again!
              </div>
              <?php
        		}
          }
        ?>
        <form method="post" class="login-form">
          <input type="text" name="email" placeholder="Email"/>
          <input type="text" name="pass" placeholder="Password"/>
          <input type="submit" placeholder="SIGN UP"/>
        </form>
        <p class="message">Already a user? <a href="index.php">Log In</a></p>
      </div>
    </div>
  </body>
</html>
