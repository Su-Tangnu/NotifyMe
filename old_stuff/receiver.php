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
					<h6><u>Create Login</u></h6>
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
			if(isset($_POST['UserName']) ){
				if(isset($_POST['Password']) ){
						$UserName = mysqli_real_escape_string($conn,strip_tags($_POST['UserName']));
						$Password = mysqli_real_escape_string($conn,strip_tags($_POST['Password']));
						$run_sql = "INSERT INTO Login (email,username,password) VALUES ('$EmailId','$UserName','$Password')";

				}
				else{
						$UserName = mysqli_real_escape_string($conn,strip_tags($_POST['UserName']));
						$run_sql = "INSERT INTO Login (email,username) VALUES ('$EmailId','$UserName')";

				}

			}
			else{
				if(isset($_POST['password']) ){
				$Password = mysqli_real_escape_string($conn,strip_tags($_POST['Password']));
				$run_sql = "INSERT INTO Login (email,password) VALUES ('$EmailId')";
				}
				else
					$run_sql = "INSERT INTO Login (email) VALUES ('$EmailId')";

			}


			//$run = mysqli_query($conn , $run_sql);
			if(mysqli_query($conn , $run_sql)){ ?>
			<script>window.location = "Index.php"; </script>
				<?php
			}
	}
