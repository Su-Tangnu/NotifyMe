<?php
	session_start();
	//$server = "localhost";
	//$db = "notifyme_db";
	//mysqli_connect($server,$username,$password,$db);
	$conn = mysqli_connect("localhost","root","","notifyme_db");
	//$conn  mysql_select_db("notifyme_db");
	if(array_key_exists("email", $_SESSION) && array_key_exists("pass", $_SESSION)){
		$UserId = $_SESSION["email"];
		$Password = $_SESSION["pass"];
		$run_sqlGetUserInfo = "SELECT * FROM users  WHERE (email = '$UserId' AND password='$Password') OR (username = '$UserId' AND password='$Password')";
		$result_sqlGetUserInfo = mysqli_query($conn,$run_sqlGetUserInfo);
		$result_UserInfo = mysqli_fetch_assoc($result_sqlGetUserInfo);
		$Username = $result_UserInfo["username"];
		$Email = $result_UserInfo["email"];
	}
	else{
		echo "<script>window.location = \"Index.php\"; </script>";
	}

?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $Username;?>'s Homepage</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
	</head>
	<body>
	<div class="container">
		<div class="jumbotron"> <h2>NOTIFY ME</h2>
		<?php echo 'Please insert the URL you wish to be notified for'?></div>
		<?php
			if(isset($_GET['edit_id'])){?>

				<h6><u>Edit URL</u></h6>
				<!--form class="col-md-6" method="post" action=""-->
				<form class="col-md-6" method="post">
					<div class="form-group">
						<label>URL</label>
						<input type="text" name= "editURL" value="<?php echo $_GET['edit_id']; ?>" class="form-control" required/>
					</div>
					<div class="form-group">
						<input type="hidden" name="edit_URL_hide" value="<?php echo $_GET['edit_id']?>"/>
						<input type="submit" name="edit_URL" value="Add the changed URL"class="btn btn-primary"/>
						<input type="submit" name="edit_CANCEL" value="Cancel"class="btn btn-primary"/>
					</div>
				</form>
	<?php }
			else{?>
				<div>
					Hello, <?php echo $Username;?>! (Not <?php echo $Username;?>?
					<a href="/NotifyMe/logout.php">
						Log out.
					</a>
					)
				</div>
				</br>
				<div>
					Need a new password? Click <a href="/NotifyMe/passwordChange.php">
						here.
					</a>
				</div>
				</br>
				<h6><u>Insert new URL</u></h6>
				<form class="col-md-6" method="post" action="">
					<div class="form-group">
						<label>URL</label>
						<input type="text" name= "newURL" class="form-control" required>
					</div>
					<div class="form-group">
						<input type="submit" name="submit_URL" value="Submit" placeholder="Submit" class="btn btn-primary">
					</div>
				</form>
			<?php
		}
			echo "<br> <br> <br>";
			$sql = "SELECT * FROM user_url_list WHERE email = '$Email'";
			$execute = mysqli_query($conn,$sql);
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
				"
				;
				$count=1;
				if($execute)
				{
				while($data = mysqli_fetch_assoc($execute)){
				/*echo $data['URL'];*/
				echo "<br>";
				echo "
					<tr>
						<td>$count</td>
						<td>$data[url]</td>";?>
						<?php if((substr($data['url'],0,4)!='HTTP')&&(substr($data['url'],0,4)!='http')&&(substr($data['url'],0,4)!='HTTPS')&&(substr($data['url'],0,4)!='https')){?>
						<?php echo "<td><a href='https://";?><?php echo $data['url'];?><?php echo"'>Visit this page</td>";}?>

						<?php if((substr($data['url'],0,4)=='HTTP')||(substr($data['url'],0,4)=='http')||(substr($data['url'],0,4)=='HTTPS')||(substr($data['url'],0,4)=='https')){?>
						<?php echo "<td><a href='";?><?php echo $data['url'];?><?php echo"'>Visit this page</td>";}?>
						<?php echo"
						<td><a href='/NotifyMe/userHomepage.php?edit_id=$data[url]' class='btn btn-success'>Edit</button></td>
						<td><a href='/NotifyMe/userHomepage.php?del_id=$data[url]' class='btn btn-danger'>Delete</button></td>
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
			$run_sql_user_url_list = "INSERT INTO user_url_list (email, url) VALUES ('$Email','$newURLVal')";

			$run_sql_insert_url = mysqli_query($conn , $run_sql_urls);
			//$run = mysqli_query($conn , $run_sql);
			if(mysqli_query($conn , $run_sql_user_url_list)){
				echo "<script>window.location = \"/NotifyMe/userHomepage.php\"; </script>";
			}
	}/*
	else{
	echo "You can add a new URL !!";
	}*/

	if(isset($_POST['edit_CANCEL'])){
				echo "<script>window.location = \"/NotifyMe/userHomepage.php\"; </script>";

	}
		if(isset($_POST['edit_URL'])){
	$editdURL = mysqli_real_escape_string($conn,strip_tags($_POST['editURL']));
	$edit_sql_urls = "UPDATE urls SET url = '$editdURL' where url='$_GET[edit_id]' ";
	$run_sql_insert_url = mysqli_query($conn , $edit_sql_urls);

	$edit_sql_user_url_list = "UPDATE user_url_list url = '$editdURL' where email='$Email' and url='$_GET[edit_id]'";
	$run_sql_insert_user_url_list = mysqli_query($conn , $edit_sql_user_url_list);

	if($run_sql_insert_url){
				echo "<script>window.location = \"/NotifyMe/userHomepage.php\"; </script>";
			}
	}

		if(isset($_GET['del_id'])){
		$del_sql = "DELETE FROM urls WHERE url = '$_GET[del_id]'";
		$del_sql_user_url_list = "DELETE FROM user_url_list where url= '$_GET[del_id]'";
	if(mysqli_query($conn , $del_sql) && mysqli_query($conn , $del_sql_user_url_list)){
				echo "<script>window.location = \"/NotifyMe/userHomepage.php\"; </script>";
			}
	}
	?>
