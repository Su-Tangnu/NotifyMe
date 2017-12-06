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
        		$Password = mysqli_real_escape_string($conn,strip_tags($_POST['pass']));
            $_SESSION["pass"] = $Password;
        		$run_sqlEmailId = "SELECT * FROM users WHERE email = '$Email'";
        		//$run_sqlUsername = "SELECT username FROM users  WHERE email = '$Email'";
        		//$run_sqlPassword = "SELECT password FROM users  WHERE email = '$Email' ";
        		if(mysqli_query($conn,$run_sqlEmailId)){  //Check if for an emailId there is a row
          		$run_sqlCorrectLogin = "SELECT * FROM users  WHERE email = '$Email' AND password='$Password'";
          		$result_sqlCorrectLogin = mysqli_query($conn,$run_sqlCorrectLogin);
              $count = mysqli_num_rows($result_sqlCorrectLogin);
          		if($count == 1) {
          			echo "<script>window.location = \"userHomepage.php\"; </script>";
          		}
          		else {
                ?>
                <div class="failure">
                  Login failed!  Please check your credentials and try again!
                </div>
                <?php
          		}
        		}
          }
        ?>
        <form method="post" class="login-form">
          <input type="text" name="email" placeholder="Email"/>
          <input type="password" name="pass" placeholder="Password"/>
          <input type="submit" placeholder="LOGIN"/>
        </form>
        <p class="message">Not registered? <a href="signup.php">Create an account</a></p>
      </div>
    </div>
  </body>
</html>
