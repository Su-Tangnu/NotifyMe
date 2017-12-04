<?php
// the message
$msg = "First line of text\nSecond line of text";

// use wordwrap() if lines are longer than 70 characters
$msg = wordwrap($msg,70);
echo "Sending Email";
// send email
mail("tangnu.suveni@gmail.com","My subject",$msg);
echo "Sent Email";
?>