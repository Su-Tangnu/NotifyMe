<?php
  session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>NotifyMe!</title>
    <link rel="stylesheet" type="text/css" href="default.css">
  </head>
  <body>
    <div class="homepage">
      <div class="form">
        <div class="title">
          NotifyMe!
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
            $_SESSION["pass"] = $Password;
        		$run_sqlEmailId = "SELECT * FROM users WHERE email = '$Email' OR username = '$Email'";
        		if(mysqli_query($conn,$run_sqlEmailId)){  //Check if for an emailId there is a row
          		$run_sqlCorrectLogin = "SELECT * FROM users  WHERE (email = '$Email' AND password='$Password') OR (username = '$Email' AND password='$Password')";
          		$result_sqlCorrectLogin = mysqli_query($conn,$run_sqlCorrectLogin);
              $count = mysqli_num_rows($result_sqlCorrectLogin);
          		if($count >= 1) {
          			echo "<script>window.location = \"userHomepage.php\"; </script>";
          		}
          		else {
                $_POST["login"] = "false";
                ?>
                <div class="message">
                  Login failed!
                </div>
                <div class="submessage">
                  Please check your credentials and try again!
                </div>
                <?php
          		}
        		}
            else{
              $_POST["login"] = "false";
              ?>
              <div class="message">
                Login failed!  That email or username is not in our database.
                Please check your credentials and try again!
              </div>
              <?php
            }
          }
        ?>
        <form method="post">
          <input type="text" name="email" placeholder="Username or Email"/>
          <input type="password" name="pass" placeholder="Password"/>
          <input type="submit" value="LOGIN" placeholder="LOGIN"/>
        </form>
        <div class="submessage">
        <?php
          if(isset($_POST["login"])){
            ?>
            Having trouble remembering your password?
            </br>
            <a href="accountRecovery.php">Try recovering your account here.</a>
            </br>
            </br>
        <?php
          }
          ?>
          Not registered? <a href="signup.php">Create an account</a>
        </div>
      </div>
    </div>
  </body>
</html>
