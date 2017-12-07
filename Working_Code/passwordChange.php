<?php
  session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Change Your NotifyMe! Password</title>
    <link rel="stylesheet" type="text/css" href="default.css">
  </head>
  <body>
    <div class="homepage">
      <div class="form">
        <div class="title">
          Change your password:
        </div>
      </br>
        <?php
        	//$server = "localhost";
        	//$db = "notifyme_db";
        	//mysqli_connect($server,$username,$password,$db);
        	$conn = mysqli_connect("localhost","root","","notifyme_db");
        	//$conn  mysql_select_db("notifyme_db");
        	if( isset($_POST['oldPass']) && isset($_POST['newPass']) && isset($_POST['newPassVerify'])){
        		if($_POST['newPass'] == $_POST['newPassVerify']){  //Check that the new passwords are the same
          		$run_sqlCheckOldPass = "SELECT * FROM users WHERE email = '$_SESSION[email]' AND password='$_POST[oldPass]'";
          		$result_sqlCheckOldPass = mysqli_query($conn,$run_sqlCheckOldPass);
              $count = mysqli_num_rows($result_sqlCheckOldPass);
          		if($count == 1) {
                $run_sqlChangePass = "UPDATE users SET password = '$_POST[newPass]' where email = '$_SESSION[email]'";
            		if($result_sqlChangePass = mysqli_query($conn,$run_sqlChangePass)){
                  ?>
                  <div class="failure">
                    Password change successful!
                    </br>
                    Click <a href="/NotifyMe/Working_Code/userHomepage.php">
          						here
          					</a>
                    to go back!
                  </div>
                  <?php
                }
          		}
          		else {
                ?>
                <div class="failure">
                  Old password was incorrect!  Please try again or click
                  <a href="/NotifyMe/Working_Code/userHomepage.php">
          						here
          				</a>
                  to go back!
                </div>
                <?php
          		}
        		}
            else{
              ?>
              <div class="failure">
                New passwords don't match!  Please try again or click
                <a href="/NotifyMe/Working_Code/userHomepage.php">
                    here
                </a>
                to go back!
              </div>
              <?php
            }
          }
        ?>
        <form method="post" class="login-form">
          <input type="text" name="oldPass" placeholder="Old Password"/>
          <input type="text" name="newPass" placeholder="New Password"/>
          <input type="text" name="newPassVerify" placeholder="Reenter New Password"/>
          <input type="submit" placeholder="CHANGE"/>
        </form>
      </div>
    </div>
  </body>
</html>
