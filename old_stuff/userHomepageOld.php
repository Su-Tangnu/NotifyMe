<?php
	//$server = "localhost";
	//$db = "notifyme_db";
	//mysqli_connect($server,$username,$password,$db);
	$conn = mysqli_connect("localhost","root","","notifyme_db");
	//$conn  mysql_select_db("notifyme_db");
	if(isset($_GET['email'])){
		$EmailId = mysqli_real_escape_string($conn,strip_tags($_GET['email']));
	}
?>
<html>
	<head>
		<title>User's NotifyMe Homepage</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
		<!--<link rel="stylesheet" type="text/css" href="default.css">-->
	</head>
	<body>
	<div class="container">
		<div class="jumbotron"> <h2>NOTIFY ME</h2>
		<?php echo 'Please insert the URL you wish to be notified for'?></div>
		<?php
			if(isset($_GET['edit_id'])){
				?>
				<h6><u>Edit URL</u></h6>
				<!--form class="col-md-6" method="post" action=""-->
				<form class="col-md-6" method="post">
					<div class="form-group">
						<label>URL</label>
						<input type="text" name= "editURL" value="<?php echo $_GET['edit_id']; ?>" class="form-control" required/>
					</div>
					<div class="form-group">
						<input type="hidden" name="edit_URL_hide" value="changed URL">
						<input type="submit" name="edit_URL" value="Add the changed URL"class="btn btn-primary">
					</div>
				</form>
				<?php
			}
			else{
				?>
				<h6><u>Insert new URL</u></h6>
				<form class="col-md-6" method="post" action="">
					<div class="form-group">
						<label>URL</label>
						<input type="text" name= "newURL" class="form-control" required>
					</div>
					<div class="form-group">
						<input type="submit" name="submit_URL"class="btn btn-primary">
					</div>
				</form>
			<?php
		}
			echo "<br> <br> <br>";
			$sql = "SELECT * FROM user_url_list WHERE email = $EmailId;";
			$execute = mysqli_query($conn,$sql);
			echo "EXECUTE IS: $execute";
			echo"
				<table class='table'>
					<thead>
						<tr>
							<th>S.No</th>
							<th>Existing URL</th>
							<th>Edit</th>
							<th>Delete</th>
						</tr>
					</thead>
				<tbody>
			";

			$count=1;
			if($execute){
				//echo "IN IF";
				//$data = mysqli_fetch_assoc($execute);
				while($data = mysqli_fetch_assoc($execute)){
					/*echo $data['URL'];*/
					echo "<br>";
					echo "
						<tr>
							<td>$count</td>
							<td>$data[URL]</td>
							<td><a href='$data[URL]'>Visit this page</td>
							<td><a href='userHomepage.php?email=$EmailId&edit_id=$data[URL]' class='btn btn-success'>Edit</button></td>
							<td><a href='userHomepage.php?email=$EmailId&del_id=$data[URL]' class='btn btn-danger'>Delete</button></td>
						</tr>
					";
					$count++;
				}
			}
			echo "</tbody> </table>";
	?>
	</div>
	</body>
</html>
<?php

	if( isset($_POST['submit_URL']) ){
			$newURLVal = mysqli_real_escape_string($conn,strip_tags($_POST['newURL']));
			$run_sql_urls = "INSERT INTO urls (url) VALUES ('$newURLVal')";
			$run_sql_user_url_list = "INSERT INTO user_url_list (email, url) VALUES ('$EmailId','$newURLVal')";
			//$run = mysqli_query($conn , $run_sql);
			if(mysqli_query($conn , $run_sql_urls) && mysqli_query($conn , $run_sql_user_url_list)){
				echo "<script>window.location = \"userHomepage.php/?email=$EmailId\"; </script>";
			}
	}/*
	else{
	echo "You can add a new URL !!";
	}*/

	if(isset($_POST['edit_URL'])){
		$del_sql = "UPDATE TABLE FROM url_list WHERE URL = '$_POST[editURL]'";
	if(mysqli_query($conn , $del_sql)){
				echo "<script>window.location = \"userHomepage.php/?email=$EmailId\"; </script>";
			}
	}

		if(isset($_GET['del_id'])){
		$del_sql = "DELETE FROM url_list WHERE URL = '$_GET[del_id]'";
	if(mysqli_query($conn , $del_sql)){
				echo "<script>window.location = \"userHomepage.php/?email=$EmailId\"; </script>";
			}
	}
