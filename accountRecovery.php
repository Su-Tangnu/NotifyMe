<?php
  session_start();
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
        <div  class="subtitle">
          Please enter either your email or username.
        </div>
        <?php
        	//$server = "localhost";
        	//$db = "notifyme_db";
        	//mysqli_connect($server,$username,$password,$db);
        	$conn = mysqli_connect("localhost","root","","notifyme_db");
        	//$conn  mysql_select_db("notifyme_db");

          $tempPass = password_hash(random_int(0,PHP_INT_MAX), PASSWORD_DEFAULT);
          $tempPassword = mysqli_real_escape_string($conn,strip_tags($tempPass));
        	if(isset($_POST['email']) && $_POST['email'] != ""){
        		$Email = mysqli_real_escape_string($conn,strip_tags($_POST['email']));
            $run_sqlAddPass = "UPDATE users SET password='$tempPassword' WHERE email = '$Email'";
            // the message
            $msg = "Here is your new password: " .$tempPass;
            // send email
            $sent = mail($Email,"NotifyMe Account Recovery",$msg);
          }
          elseif(isset($_POST['username']) && $_POST['username'] != ""){
            $Username = mysqli_real_escape_string($conn,strip_tags($_POST['username']));
            $run_sqlAddPass = "UPDATE users SET password='$tempPassword' WHERE username = '$Username'";
            $sqlEmailId = "SELECT * FROM users WHERE username = '$Username'";
            $run_sqlEmailId = mysqli_query($conn,$sqlEmailId);
            if($run_sqlEmailId){
              while($email = mysqli_fetch_assoc($run_sqlEmailId)){
                // the message
                $msg = "Here is your new password: " .$tempPass;
                // send email
                $sent = mail($email['email'],"NotifyMe Account Recovery",$msg);
              }
            }
          }
        ?>
        <form method="post" class="login-form">
          <input type="text" name="email" placeholder="Email"/>
          <input type="text" name="username" placeholder="Username"/>
          <input type="submit" value="SEND EMAIL" placeholder="SEND EMAIL"/>
        </form>
      </div>
    </div>
  </body>
</html>
