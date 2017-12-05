<?php
	//$server = "localhost";
	//$db = "software_engineering_project";
	//mysqli_connect($server,$username,$password,$db);
	$conn = mysqli_connect("localhost","root","","software_engineering_project");
	
	//$conn  mysql_select_db("software_engineering_project");
?>
<html>
	<head>
		<title>Let me know the notification URL</title>
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
						<input type="text" name= "editURL" value="<?php echo $_GET['edit_id']; ?>" class="form-control" required>
					</div>
					<div class="form-group">
						<input type="hidden" name="edit_URL_hide" value="<?php echo $_GET['edit_id']?>">
						<input type="submit" name="edit_URL" value="Add the changed URL"class="btn btn-primary">
						<input type="submit" name="edit_CANCEL" value="Cancel"class="btn btn-primary">
					</div>
				</form>			
	<?php }
			else{?>
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
			$sql = "SELECT * FROM url_list";
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
			
			while($data = mysqli_fetch_assoc($execute)){
				/*echo $data['URL'];*/
				echo "<br>";
				echo "
					<tr>
						<td>$count</td>
						<td>$data[URL]</td>";?>
						<?php if((substr($data['URL'],0,4)!='HTTP')&&(substr($data['URL'],0,4)!='http')&&(substr($data['URL'],0,4)!='HTTPS')&&(substr($data['URL'],0,4)!='https')){?>						
						<?php echo "<td><a href='https://";?><?php echo $data['URL'];?><?php echo"'>Visit this page</td>";}?>
						
						<?php if((substr($data['URL'],0,4)=='HTTP')||(substr($data['URL'],0,4)=='http')||(substr($data['URL'],0,4)=='HTTPS')||(substr($data['URL'],0,4)=='https')){?>						
						<?php echo "<td><a href='";?><?php echo $data['URL'];?><?php echo"'>Visit this page</td>";}?>	
						<?php echo"
						<td><a href='Index.php?edit_id=$data[URL]' class='btn btn-success'>Edit</button></td>
						<td><a href='Index.php?del_id=$data[URL]' class='btn btn-danger'>Delete</button></td>
					</tr>	
			";
			$count++;
			}
			
			echo "</tbody> </table>";
	?>
	
	</div>
	
	
	</body>
</html>
<?php

	if( isset($_POST['submit_URL']) ){
			$newURLVal = mysqli_real_escape_string($conn,strip_tags($_POST['newURL']));
			$run_sql = "INSERT INTO url_list (URL) VALUES ('$newURLVal')";
			//$run = mysqli_query($conn , $run_sql);
			if(mysqli_query($conn , $run_sql)){ ?>
				<script>window.location = "Index.php"; </script>
				<?php	
			}
	}/*
	else{
	echo "You can add a new URL !!";
	}*/
	
	if(isset($_POST['edit_URL'])){
	$editdURL = mysqli_real_escape_string($conn,strip_tags($_POST['editURL']));
	$edit_sql = "UPDATE url_list SET URL = '$editdURL'";
	if(mysqli_query($conn , $edit_sql)){ ?>
				<script>window.location = "Index.php"; </script>
				<?php	
			}	
	}
	
		if(isset($_GET['del_id'])){
		$del_sql = "DELETE FROM url_list WHERE URL = '$_GET[del_id]'";
	if(mysqli_query($conn , $del_sql)){ ?>
				<script>window.location = "Index.php"; </script>
				<?php	
			}	
	}
	?>