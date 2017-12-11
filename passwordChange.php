<?php
  //We use sessions to ensure that people can only see the homepage
  //once they have logged in.
  session_start();

  //mysqli_connect(server,username,password,database)
  $conn = mysqli_connect("localhost","root","","notifyme_db");
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
          //If the old password is provided, alongside the new password
          //and its verification.
        	if( isset($_POST['oldPass']) && isset($_POST['newPass']) && isset($_POST['newPassVerify'])){
            //Check that the new password and the verification are the same.
            if($_POST['newPass'] == $_POST['newPassVerify']){
              //$OldPass = password_hash($_POST['oldPass'], PASSWORD_DEFAULT);
              $OldPass = $_POST['oldPass'];
              //Check that the old password is accurate.
              //SQL to get rows with old password and email.
              $sqlCheckOldPass = "SELECT * FROM users WHERE email = '$_SESSION[email]' AND password='$OldPass'";
              //Run the SQL statement.
              $result_sqlCheckOldPass = mysqli_query($conn,$sqlCheckOldPass);

              //Get the count of how many rows were in the result.
              $count = mysqli_num_rows($result_sqlCheckOldPass);
              //If count is 1, the password is right.
          		if($count == 1) {
                //$NewPass = password_hash($_POST['newPass'], PASSWORD_DEFAULT);
                $NewPass = $_POST['newPass'];
                //SQL to set the new password.
                $sqlChangePass = "UPDATE users SET password = '$NewPass' where email = '$_SESSION[email]'";
                //Run the SQL statement.
                $result_sqlChangePass = mysqli_query($conn,$sqlChangePass);
                //If we were able to change the password, tell the user.
            		if($result_sqlChangePass){
                  ?>
                  <div class="message">
                    Password change successful!
                    </br>
                    Click <a href="/NotifyMe/userHomepage.php">
          						here
          					</a>
                    to go back!
                  </div>
                  <?php
                }
                //Otherwise, tell the user that we couldn't change the password.
                else{
                  ?>
                  <div class="message">
                    New password is bad!  Please try again or click
                    <a href="/NotifyMe/userHomepage.php">
            						here
            				</a>
                    to go back!
                  </div>
                  <?php
                }
          		}
              //Otherwise, the old password provided was wrong.
              //Tell the user and allow them to go back.
          		else {
                ?>
                <div class="message">
                  Old password was incorrect!
                </div>
                <div class="submessage">
                  Please try again or click
                  <a href="/NotifyMe/userHomepage.php">
          						here
          				</a>
                  to go back!
                </div>
                <?php
          		}
        		}
            //Otherwise, the new password and verification don't match.
            else{
              ?>
              <div class="message">
                New passwords don't match!
              </div>
              <div class="submessage">
                Please try again or click
                <a href="/NotifyMe/userHomepage.php">
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
          <input type="text" name="newPassVerify" placeholder="Re-enter New Password"/>
          <input type="submit" value="CHANGE" placeholder="CHANGE"/>
        </form>
      </div>
    </div>
  </body>
</html>
