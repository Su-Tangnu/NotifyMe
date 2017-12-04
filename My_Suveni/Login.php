<?php
//$server = "localhost";
//$db = "software_engineering_project";
//mysqli_connect($server,$username,$password,$db);
$conn = mysqli_connect("localhost","root","","software_engineering_project");

//$conn  mysql_select_db("software_engineering_project");
?>
<html>
	<head>
	<title>Login</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
	</head>
	<body>
		<h6><u>Enter Credentials to  Login</u></h6>
		<form class="col-md-6" method="post" action="">
		<div class="form-group">
			<label>EmailId</label>
			<input type="text" name= "EmailId" class="form-control" required>
		</div>
		<div class="form-group">
			<label>User Name</label>
			<input type="text" name= "UserName" class="form-control">
		</div>
		<div class="form-group">
			<label>Password</label>
			<input type="text" name= "Password" class="form-control">
		</div>
		<div class="form-group">
			<input type="submit" name="submit_LOGIN"class="btn btn-primary">
		</div>
		</form>
	</body>
</html>
<?php
if( isset($_POST['submit_LOGIN']) ){
	$EmailId = mysqli_real_escape_string($conn,strip_tags($_POST['EmailId']));
	$UserName = mysqli_real_escape_string($conn,strip_tags($_POST['UserName']));
	$Password = mysqli_real_escape_string($conn,strip_tags($_POST['Password']));
	$run_sqlEmailId = "SELECT * FROM Login  WHERE email = '$EmailId'";
	//$run_sqlUsername = "SELECT username FROM Login  WHERE email = '$EmailId'";
	//$run_sqlPassword = "SELECT password FROM Login  WHERE email = '$EmailId' ";
	if(mysqli_query($conn,$run_sqlEmailId)){//Check if for an emailId there is a row
			if( (isset($_POST['Password'])) && (isset($_POST['UserName']))  ){
				$run_sqlUsername = "SELECT * FROM Login  WHERE email = '$EmailId' AND username='$UserName' AND password='$Password'";
				$resultUsernamePassword = mysqli_query($conn,$run_sqlUsername);
				$count = mysqli_num_rows($result);
				if($count == 1) {?>
					<script>window.location = "Index.php"; </script>
					<?php

				 }
				 else {
					 echo "Invalid UserName or Password";
					 ?>
					 <script>window.location = "Login.php"; </script>
					 <?php
				 
				 }

			}
			if(isset($_POST['Password']) ){
				$run_sqlUsername = "SELECT * FROM Login  WHERE email = '$EmailId' AND password='$Password'";
				$resultPassword = mysqli_query($conn,$run_sqlUsername);
				$count = mysqli_num_rows($result);
				if($count == 1) {?>
					<script>window.location = "Index.php"; </script>
					<?php

				 }
				 else {
					 echo "Invalid UserName or Password";
					 ?>
					 <script>window.location = "Login.php"; </script>
					 <?php
				 
				 }
				
			}
			if(isset($_POST['UserName']) ){
				
			}
		$resultUsername = mysqli_query($conn,$run_sqlUsername);
		$rowUsername = mysqli_fetch_array($run_sqlUsername,MYSQLI_ASSOC);
		/*If corresponds to username part of the row corresponding to the emailId-specifically checks only the username
		*If part should as well , check when the username is found then check the password.
		*else corresponds to password part of the row corresponding to the emailId when no username is found-specifically checks only the password
		*/
		if(!(is_null($rowUsername['username']))) {//row for the email_id has the username ,need to check if the entered username = the row username
				$run_sql = "SELECT * FROM Login  WHERE email = '$EmailId' AND username='$UserName'";
				$result = mysqli_query($conn,$run_sql);
				$row = mysqli_fetch_array($result,MYSQLI_ASSOC);      
				$count = mysqli_num_rows($result);
				//corresponding to the username a row was found
				if($count == 1) {
					
					
					
					?>
					<script>window.location = "Index.php"; </script>
					<?php
						 
				 }
				 //corresponding to the username no row was found
				 else{
						 echo "Invalid Login,Wrong UserName";
						 sleep(2500000000);
						 ?>
						 <script>window.location = "Login.php"; </script>
						 <?php
				 
				 }
		
		}
		//This else  would mean that username does not exist, so in else check if password would check
		else{
			$resultPassword = mysqli_query($conn,$run_sqlPassword);
			$rowPassword = mysqli_fetch_array($run_sqlPassword,MYSQLI_ASSOC);
			
			if(!(is_null($rowUsername['password']))) {//row for the email_id has the password ,need to check if the entered password = the row password
				$run_sql = "SELECT * FROM Login  WHERE email = '$EmailId' AND password='$UserName'";
				$result = mysqli_query($conn,$run_sql);
				$row = mysqli_fetch_array($result,MYSQLI_ASSOC);      
				$count = mysqli_num_rows($result);
				//Found a row for a password
				if($count == 1) {?>
					<script>window.location = "Index.php"; </script>
					<?php
						 
				 }
				 else{
						 echo "Invalid Login,Wrong Password";
						 sleep(2500000000);
						 ?>
						 <script>window.location = "Login.php"; </script>
						 <?php
				 
				 }
		
		}
		}

	
	}
	else{//There is no row for the entered emailID , That means entered value is wrong
		 echo "Invalid Login,Wrong Email Id";
		 sleep(2500000000);
		 ?>
		 <script>window.location = "Login.php"; </script>
		 <?php
	}
	
}


?>