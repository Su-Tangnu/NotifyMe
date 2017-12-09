<?php
	//We use sessions to ensure that people can only see the homepage
	//once they have logged in.
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
		echo "<script>window.location = \"/NotifyMe/Index.php\"; </script>";
	}

?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $Username;?>'s Homepage</title>
		<link rel="stylesheet" type="text/css" href="userHomepage.css">
	</head>
	<body>
		<div class="userpage">
			<div class="banner">
				NotifyMe!
			</div>
			<div class="subbanner">
				We update when they update!
			</br>
			</br>
			</div>
				<?php
					if(isset($_GET['edit_id'])){
				?>
						<div class="body">
							Edit URL
						</div>
						<form class="col-md-6" method="post">
								<input type="text" name= "editURL" value="<?php echo $_GET['edit_id']; ?>" class="form-control" required/>
								<input type="hidden" name="edit_URL_hide" value="<?php echo $_GET['edit_id']?>"/>
								<input type="submit" name="edit_URL" value="Change"/>
								<input type="submit" name="edit_CANCEL" value="Cancel"/>
						</form>
				<?php
					}
					else{
				?>
						<div class="body">
							Hello, <?php echo $Username;?>!
						</div>
						<div class="subbody">
							(Not <?php echo $Username;?>?
							<a href="/NotifyMe/logout.php">
								Log out.
							</a>
							)
							</br>
							Need a new password? Click <a href="/NotifyMe/passwordChange.php">
								here.
							</a>
						</br>
						</br>
						</br>
						</br>
						</div>
						<div class="body">
							Enter the URL you want to track:
						</div>
						<form class="url-form"method="post">
								<input type="text" name= "newURL" required/>
								<input type="submit" name="submit_URL" value="Submit" placeholder="Submit"/>
						</form>
				<?php
					}
				?>
				</br>
				</br>
				<div class="table-title">
					<h3>List of Websites</h3>
				</div>
				<?php
					$sql = "SELECT * FROM user_url_list WHERE email = '$Email'";
					$execute = mysqli_query($conn,$sql);
					echo"
						<table class='table'>
							<thead>
								<tr>
									<th>S.No</th>
									<th>Existing URL</th>
									<th>Visit</th>
									<th>Edit</th>
									<th>Delete</th>
								</tr>
							</thead>
						<tbody>
						";
						$count=1;
						if($execute){
							while($data = mysqli_fetch_assoc($execute)){
								/*echo $data['URL'];*/
								echo "
								<tr>
									<td>$count</td>
									<td>$data[url]</td>";
								if((substr($data['url'],0,4)!='HTTP')&&(substr($data['url'],0,4)!='http')&&(substr($data['url'],0,4)!='HTTPS')&&(substr($data['url'],0,4)!='https')){
									echo "<td><a href='http://";
									echo $data['url'];
									echo"'>Visit this page</td>";
								}
								else{
									echo "<td><a href='";
									echo $data['url'];
									echo"'>Visit this page</td>";
								}
								echo"
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
		</div>
	</body>
</html>
<?php

	if( isset($_POST['submit_URL']) ){
			$newURLVal = mysqli_real_escape_string($conn,strip_tags($_POST['newURL']));
			$sql_urls = "INSERT INTO urls (url) VALUES ('$newURLVal')";
			$sql_user_url_list = "INSERT INTO user_url_list (email, url) VALUES ('$Email','$newURLVal')";
			$run_sql_insert_url = mysqli_query($conn , $sql_urls);
			$run_sql_insert_user_url_list = mysqli_query($conn , $sql_user_url_list);
			$properURL = $newURLVal;
      if((substr($properURL,0,4)!='HTTP')&&(substr($properURL,0,4)!='http')&&(substr($properURL,0,4)!='HTTPS')&&(substr($properURL,0,4)!='https')){
        $properURL = "http://" . $properURL;
      }
			$headers = get_headers($properURL, 1);
      if($headers){
				if (array_key_exists("Last-Modified", $headers)){
          $lastModified = "Last-Modified";
          $sql_update = "UPDATE urls SET lastModified = '$headers[$lastModified]' WHERE url = '$newURLVal'";
          $execute_update = mysqli_query($conn,$sql_update);
          if($execute_update){
            echo "Updated $newURLVal Last-Modified.";
            echo "</br>";
          }
        }
        if(array_key_exists("ETag", $headers)){
          $sql_update = "UPDATE urls SET etag = '$headers[ETag]' WHERE url = '$newURLVal'";
          $execute_update = mysqli_query($conn,$sql_update);
          if($execute_update){
            echo "Updated $newURLVal ETag.";
            echo "</br>";
          }
        }
				elseif(!array_key_exists("Last-Modified", $headers)){
					$run_delete_url = mysqli_query($conn,"DELETE FROM urls WHERE url = '$newURLVal'");
				}
			}
			if($run_sql_insert_user_url_list && !$run_delete_url){
				echo "<script>window.location = \"/NotifyMe/userHomepage.php\"; </script>";
			}
			else{
				?>
				<script>alert("URL was not added!\n\nSorry, this URL does not have a Last-Modified value or an Etag value.")</script>
				<?php
			}
	}

	if(isset($_POST['edit_CANCEL'])){
				echo "<script>window.location = \"/NotifyMe/userHomepage.php\"; </script>";
	}
	if(isset($_POST['edit_URL'])){
		$editdURL = mysqli_real_escape_string($conn,strip_tags($_POST['editURL']));
		$edit_sql_urls = "UPDATE urls SET url = '$editdURL' WHERE url='$_GET[edit_id]' ";
		$run_sql_insert_url = mysqli_query($conn , $edit_sql_urls);
		$edit_sql_user_url_list = "UPDATE user_url_list SET url = '$editdURL' WHERE email='$Email' AND url='$_GET[edit_id]'";
		$run_sql_insert_user_url_list = mysqli_query($conn , $edit_sql_user_url_list);
		if($run_sql_insert_url){
				echo "<script>window.location = \"/NotifyMe/userHomepage.php\"; </script>";
		}
	}
	if(isset($_GET['del_id'])){
		$del_sql = "DELETE FROM urls WHERE url = '$_GET[del_id]'";
		$del_sql_user_url_list = "DELETE FROM user_url_list WHERE url= '$_GET[del_id]'";
		if(mysqli_query($conn , $del_sql) && mysqli_query($conn , $del_sql_user_url_list)){
				echo "<script>window.location = \"/NotifyMe/userHomepage.php\"; </script>";
		}
	}
?>
