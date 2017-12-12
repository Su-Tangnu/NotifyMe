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
    <title>Recover Your NotifyMe Account</title>
    <link rel="stylesheet" type="text/css" href="default.css">
  </head>
  <body>
    <div class="homepage">
      <div class="form">
        <div class="title">
          Account Recovery:
        </div>
        </br>
        <?php
          //Create a temporary password for the user.
          $tempPass = password_hash(random_int(0,PHP_INT_MAX), PASSWORD_DEFAULT);
          //and make sure that it is escaped and safe for the database.
          $tempPassword = mysqli_real_escape_string($conn,strip_tags($tempPass));
          //If the user provided a (non-empty) email, use it to send them the
          //temporary password.
        	if(isset($_POST['email']) && $_POST['email'] != ""){
            //The message containing the new password.
            $msg = "Here is your new password: " . $tempPass;
            //Send the email
            $sent = mail($_POST['email'],"NotifyMe Account Recovery",$msg);
            //If the mail was sent, tell the user and provide a link back for
            //them to sign in with their new password.
            if($sent){
              //sanitize the email string
              $Email = mysqli_real_escape_string($conn,strip_tags($_POST['email']));
              //SQL command to set the new temp password.
              $sqlAddTempPass = "UPDATE users SET password='$tempPassword' WHERE email = '$Email'";
              //Run the previous SQL command.
              $run_sqlAddTempPass = mysqli_query($conn,$sqlAddTempPass);
              //If the update was unsuccessful, tell the user to ignore the email.
              if(!$run_sqlAddTempPass){
                ?>
                <div  class="message">
                  Please ignore the email message sent to <?php echo $_POST['email'];?>.
                </div>
                <div class="submessage">
                  We were unable to change your password from what it was before.
                  </br>
                  Please click
                  <a id="linkToIndex" href="/NotifyMe/Index.php">
                    here
                  </a>
                  to try logging in again or reenter your email or username to try
                  resetting your password once more.
                </div>
                <?php
              }
              //Else, the update was successful, so tell the user that
              //the email has been sent and link them back to where
              //they can log in.
              else{
                ?>
                <div  class="message">
                  An email has been sent to <?php echo $_POST['email'];?>.
                </div>
                <div class="submessage">
                  </br>
                  Click
                  <a id="linkToIndexSuccess" href="/NotifyMe/Index.php">
                    here
                  </a>
                  to log in.
                </div>
                <?php
              }
            }
            else{
              //Let them know that we were unable to send the email.
              //and that the password is what it was before.
              ?>
              <div  class="message">
                We were unable to send an email to <?php echo $_POST['email'];?>.
              </div>
              <div class="submessage">
                </br>
                Your password is what it was previously.
                </br>
                Click
                <a id="linkToIndex" href="/NotifyMe/Index.php">
                  here
                </a>
                to try logging in again, or
                </br>
                reenter your email or username to try
                resetting your password once more.
              </div>
              <?php
            }
          }
          elseif(isset($_POST['username']) && $_POST['username'] != ""){
            //sanitize the username string
            $Username = mysqli_real_escape_string($conn,strip_tags($_POST['username']));
            //SQL command to get all user info associated with the username.
            $sqlEmailId = "SELECT * FROM users WHERE username = '$Username'";
            //Run the previous SQL command.
            $run_sqlEmailId = mysqli_query($conn,$sqlEmailId);
            //If we were succcessful in getting the user info for the username,
            //email the user.
            if($run_sqlEmailId){
              //loop over the info found per row using an associative array.
              //Should only loop once.
              while($email = mysqli_fetch_assoc($run_sqlEmailId)){
                //The message containing the new password.
                $msg = "Here is your new password: " .$tempPass;
                //Send the email.
                $sent = mail($email['email'],"NotifyMe Account Recovery",$msg);
                //If the mail was sent, tell the user and provide a link back for
                //them to sign in with their new password.
                if($sent){
                  //set the new password for the username.
                  $sqlAddTempPass = "UPDATE users SET password='$tempPassword' WHERE username = '$Username'";
                  //Run the previous SQL command.
                  $run_sqlAddTempPass = mysqli_query($conn,$sqlAddTempPass);
                  //If the update was unsuccessful, tell the user to ignore the email.
                  if(!$run_sqlAddTempPass){
                    ?>
                    <div  class="message">
                      Please ignore the email message sent to <?php echo $email['email'];?>.
                    </div>
                    <div class="submessage">
                      We were unable to change your password from what it was before.
                      </br>
                      Please click
                      <a id="linkToIndex" href="/NotifyMe/Index.php">
                        here
                      </a>
                      to try logging in again or reenter your email or username to try
                      resetting your password once more.
                    </div>
                    <?php
                  }
                  //Otherwise, tell the user that the email was sent and
                  //link them back to where they can log in.
                  else{
                    ?>
                    <div  class="message">
                      An email has been sent to <?php echo $email['email'];?>.
                    </div>
                    <div class="submessage">
                      </br>
                      Click
                      <a id="linkToIndex" href="/NotifyMe/Index.php">
                        here
                      </a>
                      to log in.
                    </div>
                    <?php
                  }
                }
                //Let them know that we were unable to send the email.
                //and that the password is what it was before.
                else{
                  ?>
                  <div  class="message">
                    We were unable to send an email to <?php echo $email['email'];?>.
                  </div>
                  <div class="submessage">
                    </br>
                    Your password is what it was previously.
                    </br>
                    Click
                    <a id="linkToIndex" href="/NotifyMe/Index.php">
                      here
                    </a>
                    to try logging in again, or
                    </br>
                    reenter your email or username to try
                    resetting your password once more.
                  </div>
                  <?php
                }
              }
            }
            //Otherwise, let them know that we were unable to get
            //the email address associated with that username.
            else{
              ?>
              <div  class="message">
                We were unable to obtain the email address
                associated with the username, <?php echo $_POST['username'];?>.
              </div>
              <div class="submessage">
                </br>
                Your password is what it was previously.
                </br>
                Click
                <a id="linkToIndex" href="/NotifyMe/Index.php">
                  here
                </a>
                to try logging in again, or
                </br>
                reenter your email or username to try
                resetting your password once more.
              </div>
              <?php
            }
          }
          //If the user did not provide an email or a username,
          //let them know that they must in order to recover their account.
          elseif((isset($_POST['email']) && $_POST['email'] == "") || (isset($_POST['username']) && $_POST['username'] == "")){
            ?>
            <div  class="message">
              You must enter an email or a username.
            </div>
            <div class="submessage">
              </br>
              Please click
              <a id="linkToIndex" href="/NotifyMe/Index.php">
                here
              </a>
              to try logging in again or enter your email or username to try
              resetting your password once more.
            </div>
            <?php
          }
          //If none of the above are the case, the user hasn't entered anything yet.
          //Kindly instruct them to do so.
          else{
            ?>
            <div  class="subtitle">
              Please enter either your email or username.
            </div>
            <?php
          }
        ?>
        <form method="post" class="login-form">
          <input id="emailInput" type="text" name="email" placeholder="Email"/>
          <input id="usernameInput" type="text" name="username" placeholder="Username"/>
          <input id="accountRecoveryInput" type="submit" value="SEND EMAIL" placeholder="SEND EMAIL"/>
        </form>
      </div>
    </div>
  </body>
</html>
