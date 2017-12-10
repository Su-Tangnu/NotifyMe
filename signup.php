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
          //If the user provided an email (that is not blank)
          //and password, try signing them up.
        	if(isset($_POST['email']) && isset($_POST['pass']) && $_POST['email'] != ''){
            //strip_tags and mysqli_real_escape_string to help prevent sql injection.
            $Email = mysqli_real_escape_string($conn,strip_tags($_POST['email']));
            //Session variable used to store email and verify that the user has logged in:
            $_SESSION["email"] = $Email;

            //$Pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
            $Pass = $_POST['pass'];
        		$Password = mysqli_real_escape_string($conn,strip_tags($Pass));
            $run_sqlCreateAccount = "INSERT INTO users (email, username, password) VALUES ('$Email', '$Email', '$Password')";
            //Session variable used to store password and verify that the user has logged in:
            $_SESSION["pass"] = $Password;
            //If the user provided a username (that is not blank), set it
            //and add it to the INSERT statement where appropriate.
            if(isset($_POST['username']) && $_POST['username'] != ''){
              $Username = mysqli_real_escape_string($conn,strip_tags($_POST["username"]));
              $_SESSION["username"] = $Username;
              $run_sqlCreateAccount = "INSERT INTO users (email, username, password) VALUES ('$Email', '$Username', '$Password')";
            }
            //Run the insertion.  If it succeeds, take the user to their homepage.
        		if(mysqli_query($conn,$run_sqlCreateAccount)){
              echo "<script>window.location = \"userHomepage.php\"; </script>";
        		}
            //Else, if it fails, but the email and password can be inserted,
            //it must be the username that is the problem.
        		elseif(mysqli_query($conn,"INSERT INTO users (email, password) VALUES ('$Email', '$Password')")){
              //We must delete the email and password we just inserted
              //since the user hasn't been signed up.
              mysqli_query($conn,"DELETE FROM users WHERE (email='$Email' AND password='$Password')");
              //Clears the $_SESSION array since sign up failed.
              session_unset();
              ?>
              <div class="message">
                Sign up failed!  That username is already taken.
                Please select a new username and try again!
              </div>
              <?php
        		}
            //If we failed to sign up the user and the username wasn't the problem,
            //the email address must already be taken.
            else{
              //Clears the $_SESSION array since sign up failed.
              session_unset();
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
          //If the user tried to sign up without providing an email,
          //reject them and tell them that they must provide an email address.
          elseif(isset($_POST['email']) && $_POST['email'] == ''){
            ?>
            <div class="message">
              Sign up failed!
            </div>
            <div class="submessage">
              You must enter an email address!
              </br>
              Please choose an email address and try again!
            </div>
            <?php
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
