<?php
// the message
$msg = "First line of text\nSecond line of text";

// use wordwrap() if lines are longer than 70 characters
$msg = wordwrap($msg,70);
echo "Sending Email";
// send email
$sent = mail("matthewd.manning@gmail.com","My subject",$msg);
if($sent){
  echo "Sent Email";
}
?>
