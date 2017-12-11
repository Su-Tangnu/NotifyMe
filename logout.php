<?php
  //We have to start the session in order to
  //remove the session variables and destroy the session.
  session_start();

  //Clears the $_SESSION array, signing the user out.
  session_unset();

  //Not necessary, but proper form as it deletes all of the session's data.
  session_destroy();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Logged out.</title>
    <link rel="stylesheet" type="text/css" href="default.css">
  </head>
  <body>
    <div class="homepage">
      <div class="form">
        <div class="title">
          Logged Out!
        </div>
        <div class="submessage">
          </br>
          You have been successfully logged out.
          </br>
          Click
          <a href="/NotifyMe/Index.php">
            here
          </a>
          to log back in.
        </div>
      </div>
    </div>
  </body>
