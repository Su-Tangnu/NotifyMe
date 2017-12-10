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
          //If the user provided an email or username (that is not blank)
          //and password, try signing them up.
          if(isset($_POST['email']) && isset($_POST['pass']) && $_POST['email'] != ''){
            //This variable actually can be the email or username.
            //strip_tags and mysqli_real_escape_string to help prevent sql injection.
            $Email = mysqli_real_escape_string($conn,strip_tags($_POST['email']));
            //Session variable used to store email and verify that the user has logged in:
            $_SESSION["email"] = $Email;

            //$Pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
            $Pass = $_POST['pass'];
        		$Password = mysqli_real_escape_string($conn,strip_tags($Pass));
            //Session variable used to store password and verify that the user has logged in:
            $_SESSION["pass"] = $Password;

            //Get all users that match the email or username entered in the "email" variable.
        		$run_sqlEmailId = "SELECT * FROM users WHERE email = '$Email' OR username = '$Email'";
            //Run the query and check that their is an email or username with the email value.
            if(mysqli_query($conn,$run_sqlEmailId)){
              //Get all users that match the email or username entered in the "email" variable
              //and match the password entered.
              $run_sqlCorrectLogin = "SELECT * FROM users  WHERE (email = '$Email' AND password='$Password') OR (username = '$Email' AND password='$Password')";
          		$result_sqlCorrectLogin = mysqli_query($conn,$run_sqlCorrectLogin);
              $count = mysqli_num_rows($result_sqlCorrectLogin);
              //if there was (at least) one instance where user ID (email or username)
              //and password matched, log the in and send them to their homepage.
              if($count >= 1) {
          			echo "<script>window.location = \"userHomepage.php\"; </script>";
          		}
          		else {
                //login failed, so set login to false.
                $_POST['login'] = "false";
                //Clears the $_SESSION array since the credentials were invalid.
                session_unset();
                ?>
                <div class="message">
                  Login failed!
                </div>
                <div class="submessage">
                  That email or username is not in our database.
                  Please check your credentials and try again!
                </div>
                <?php
          		}
        		}
            else{
              //login failed, so set login to false.
              $_POST['login'] = "false";
              //Clears the $_SESSION array since the credentials were invalid.
              session_unset();
              ?>
              <div class="message">
                Login failed!
              </div>
              <div class="submessage">
                That email or username is not in our database.
                Please check your credentials and try again!
              </div>
              <?php
            }
          }
          elseif(isset($_POST['email']) && $_POST['email'] == ''){
            //login failed, so set login to false.
            $_POST['login'] = "false";
            //Clears the $_SESSION array since the credentials were invalid.
            session_unset();
            ?>
            <div class="message">
              Login failed!
            </div>
            <div class="submessage">
              You must enter an email or username.
              Please check your credentials and try again!
            </div>
            <?php
          }
        ?>
        <form method="post">
          <input type="text" name="email" placeholder="Username or Email"/>
          <input type="password" name="pass" placeholder="Password"/>
          <input type="submit" value="LOGIN" placeholder="LOGIN"/>
        </form>
        <div class="submessage">
        <?php
          //If "login" is set in post, it is set to failed since that is the only
          //possible assigned value.  Since login failed, offer them account recovery.
          if(isset($_POST['login'])){
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
