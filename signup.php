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
            //$Pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
            $Pass = $_POST['pass'];
        		$Password = mysqli_real_escape_string($conn,strip_tags($Pass));
            $run_sqlCreateAccount = "INSERT INTO users (email, username, password) VALUES ('$Email', '$Email', '$Password')";
            if(isset($_POST['username']) && $_POST['username'] != ''){
              $Username = mysqli_real_escape_string($conn,strip_tags($_POST["username"]));
              $_SESSION["username"] = $Username;
              $run_sqlCreateAccount = "INSERT INTO users (email, username, password) VALUES ('$Email', '$Username', '$Password')";
            }
        		if(mysqli_query($conn,$run_sqlCreateAccount)){
              echo "<script>window.location = \"userHomepage.php/\"; </script>";
        		}
        		elseif(mysqli_query($conn,"INSERT INTO users (email, password) VALUES ('$Email', '$Password')")){
              mysqli_query($conn,"DELETE FROM users WHERE email='$Email' AND password='$Password')");
              ?>
              <div class="message">
                Sign up failed!  That username is already taken.
                Please select a new username and try again!
              </div>
              <?php
        		}
            else{
              ?>
              <div class="message">
                Sign up failed!
              </div>
              <div class="submessage">
                That email address already has an account.
                </br>
                Please enter a new email address and try again!
              </div>
              <?php
            }
          }
        ?>
        <form method="post" class="login-form">
          <input type="text" name="email" placeholder="Email"/>
          <input type="text" name="username" placeholder="Username (optional)"/>
          <input type="text" name="pass" placeholder="Password (optional)"/>
          <input type="submit" value="SIGN UP" placeholder="SIGN UP"/>
        </form>
        <div class="submessage">
          Already a user? <a href="index.php">Log In</a>
        </div>
      </div>
    </div>
  </body>
</html>
