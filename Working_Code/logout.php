<?php
  session_start();
  // remove all session variables
  session_unset();
  // destroy the session
  session_destroy();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Logged out.</title>
    <link rel="stylesheet" type="text/css" href="default.css">
  </head>
  <body>
    <div class=form>
      You have been successfully logged out.
      </br>
      Click
      <a href="/NotifyMe/Working_Code/Index.php">
        here
      </a>
      to log back in.
    </div>
  </body>
