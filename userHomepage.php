<?php
	//We use sessions to ensure that people can only see the homepage
	//once they have logged in.
	session_start();

	//mysqli_connect(server,username,password,database)
	$conn = mysqli_connect("localhost","root","","notifyme_db");

	//If the user is signed in (email and password are set),
	//set the user's info.
	if(array_key_exists("email", $_SESSION) && array_key_exists("pass", $_SESSION)){
		//Technically, $_SESSION["email"] can have either the email or username of the user,
		//so we call it UserID.
		$UserId = $_SESSION["email"];
		$Password = $_SESSION["pass"];
		//SQL command to get the user's info.
		$sqlGetUserInfo = "SELECT * FROM users  WHERE (email = '$UserId' AND password='$Password') OR (username = '$UserId' AND password='$Password')";
		//Run the previous SQL command.
		$result_sqlGetUserInfo = mysqli_query($conn,$sqlGetUserInfo);
		//Get the user's info in an associative array.
		$UserInfo = mysqli_fetch_assoc($result_sqlGetUserInfo);
		//Get the username and email from UserInfo.
		$Username = $UserInfo["username"];
		$Email = $UserInfo["email"];
	}
	//Otherwise, the user isn't logged in.
	//Send them back to the log in page (index.php).
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
					//If the user is editing a URL, give them the edit form.
					if(isset($_GET['edit_id'])){
				?>
						<div class="body">
							Edit URL
						</div>
						<form method="post">
								<input type="text" name= "editURL" value="<?php echo $_GET['edit_id']; ?>" required/>
								<input type="submit" name="edit_URL" value="Change"/>
								<input type="submit" name="edit_CANCEL" value="Cancel"/>
						</form>
				<?php
					}
					//Otherwise, display the normal user's homepage, giving access to
					//logging the user out, changing their password, and adding other URLs.
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
					echo"
						<table class='table'>
							<thead>
								<tr>
									<th>URL Number</th>
									<th>Existing URL</th>
									<th>Visit</th>
									<th>Edit</th>
									<th>Delete</th>
								</tr>
							</thead>
						<tbody>
						";
						//URL number
						$count=1;
						//Get the user's list of websites and put them in the table.
						$sql = "SELECT * FROM user_url_list WHERE email = '$Email'";
						$execute = mysqli_query($conn,$sql);
						//If the user's list returns anything, put it in the table.
						if($execute){
							//Loop through the data per row with an associative array.
							while($data = mysqli_fetch_assoc($execute)){
								echo "
								<tr>
									<td>$count</td>
									<td>$data[url]</td>";
								//Add http:// to the url if it's not there so that the link works.
								if((substr($data['url'],0,4)!='HTTP')&&(substr($data['url'],0,4)!='http')&&(substr($data['url'],0,4)!='HTTPS')&&(substr($data['url'],0,4)!='https')){
									echo "<td><a href='http://";
									echo $data['url'];
									echo"'>Visit this page</td>";
								}
								//Otherwise, it already had http, so you don't need to add it.
								else{
									echo "<td><a href='";
									echo $data['url'];
									echo"'>Visit this page</td>";
								}
								echo"
									<td><a href='/NotifyMe/userHomepage.php?edit_id=$data[url]'>Edit</td>
									<td><a href='/NotifyMe/userHomepage.php?del_id=$data[url]'>Delete</td>
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
	//If we have a link to add, add it to the database.
	if(isset($_POST['submit_URL']) ){
		//sanitize the new URL string.
		$newURLVal = mysqli_real_escape_string($conn,strip_tags($_POST['newURL']));
		//SQL to insert the new URL into the database both in the url table and
		//in the user_url_list table.
		$sql_urls = "INSERT INTO urls (url) VALUES ('$newURLVal')";
		$sql_user_url_list = "INSERT INTO user_url_list (email, url) VALUES ('$Email','$newURLVal')";
		//Run the previous two queries with the next two statements.
		$run_sql_insert_url = mysqli_query($conn , $sql_urls);
		$run_sql_insert_user_url_list = mysqli_query($conn , $sql_user_url_list);

		//properURL is the new URL, but it is guaranteed to have http://
		//in front of it with the following conditional.
		$properURL = $newURLVal;
    if((substr($properURL,0,4)!='HTTP')&&(substr($properURL,0,4)!='http')&&(substr($properURL,0,4)!='HTTPS')&&(substr($properURL,0,4)!='https')){
      $properURL = "http://" . $properURL;
    }
		//get the headers from the new URL as an associative array.
		//(The 1 as the second argument means return it as an associative array.)
		$headers = get_headers($properURL, 1);
		//If the headers were returned, check that they have either Last-Modified
		//or an ETag.  If not, delete it from the database and tell the user that
		//we cannot add it because it does not have the necessary headers.
    if($headers){
			//If Last-Modified exists, set it for the URL in the database.
			if (array_key_exists("Last-Modified", $headers)){
        $lastModified = "Last-Modified";
        $sql_update = "UPDATE urls SET lastModified = '$headers[$lastModified]' WHERE url = '$newURLVal'";
        $execute_update = mysqli_query($conn,$sql_update);
        /*if($execute_update){
          echo "Updated $newURLVal Last-Modified.";
          echo "</br>";
        }*/
      }
			//If ETag exists, set it for the URL in the database.
      if(array_key_exists("ETag", $headers)){
        $sql_update = "UPDATE urls SET etag = '$headers[ETag]' WHERE url = '$newURLVal'";
        $execute_update = mysqli_query($conn,$sql_update);
        /*if($execute_update){
          echo "Updated $newURLVal ETag.";
          echo "</br>";
        }*/
      }
			//If both Last-Modified and ETag don't exist, delete the new URL
			//from the database (and tell the user that we can't add it).
			elseif(!array_key_exists("Last-Modified", $headers)){
				$run_delete_url = mysqli_query($conn,"DELETE FROM urls WHERE url = '$newURLVal'");
			}
		}
		//If we cannot get the headers, delete the URL from the database and tell the user that
		//we cannot add it because it does not have the necessary headers.
		else{
			$run_delete_url = mysqli_query($conn,"DELETE FROM urls WHERE url = '$newURLVal'");
		}
		//If we added the new URL to the database and didn't later delete it,
		//go back to/refresh the homepage so that it shows up in the table.
		if($run_sql_insert_user_url_list && !$run_delete_url){
			echo "<script>window.location = \"/NotifyMe/userHomepage.php\"; </script>";
		}
		//Otherwise, tell the user that we were unable to add the URL.
		else{
			?>
			<script>alert("URL was not added!\n\nSorry, this URL does not have a Last-Modified value or an Etag value.")</script>
			<?php
		}
	}

	//If the user decides to cancel editing the URL, go back to the homepage.
	if(isset($_POST['edit_CANCEL'])){
				echo "<script>window.location = \"/NotifyMe/userHomepage.php\"; </script>";
	}
	//If the user wants to edit a URL, (edit_URL indicates the user wants to change it)
	//change the old value, edit_id to the new value, editURL.
	if(isset($_POST['edit_URL']) && isset($_POST['editURL'])){
		//Sanitize the edited URL.
		$editedURL = mysqli_real_escape_string($conn,strip_tags($_POST['editURL']));

		$run_sql_insert_user_url_list = false;

		//properURL is the new URL, but it is guaranteed to have http://
		//in front of it with the following conditional.
		$properURL = $editedURL;
    if((substr($properURL,0,4)!='HTTP')&&(substr($properURL,0,4)!='http')&&(substr($properURL,0,4)!='HTTPS')&&(substr($properURL,0,4)!='https')){
      $properURL = "http://" . $properURL;
    }
		//get the headers from the new URL as an associative array.
		//(The 1 as the second argument means return it as an associative array.)
		$headers = get_headers($properURL, 1);
		//If the headers were returned, check that they have either Last-Modified
		//or an ETag.  If not, delete it from the database and tell the user that
		//we cannot add it because it does not have the necessary headers.
    if($headers){
			//If Last-Modified exists, set it for the URL in the database.
			if (array_key_exists("Last-Modified", $headers)){
        $lastModified = "Last-Modified";

				//SQL to update the URL in both the urls and user_url_list tables.
				//Only update urls if this is the only user with this url.
				//Otherwise, insert.
				$edit_sql_urls = "INSERT INTO urls (url) VALUES ('$editedURL')";
				$get_user_url_list_info = "SELECT * FROM user_url_list WHERE url='$_GET[edit_id]' ";
				$run_get_info = mysqli_query($conn , $get_user_url_list_info);
				$count = mysqli_num_rows($run_get_info);
				if($count == 1){
					$edit_sql_urls = "UPDATE urls SET url = '$editedURL' WHERE url='$_GET[edit_id]' ";
				}
				$edit_sql_user_url_list = "UPDATE user_url_list SET url = '$editedURL' WHERE email='$Email' AND url='$_GET[edit_id]'";
				//Run the previous two queries.
				$run_sql_insert_url = mysqli_query($conn , $edit_sql_urls);
				$run_sql_insert_user_url_list = mysqli_query($conn , $edit_sql_user_url_list);
				//Update Last-Modified for the new URL.
        $sql_update = "UPDATE urls SET lastModified = '$headers[$lastModified]' WHERE url = '$editedURL'";
        $execute_update = mysqli_query($conn,$sql_update);
        /*if($execute_update){
          echo "Updated $editedURL Last-Modified.";
          echo "</br>";
        }*/
      }
			//If ETag exists, set it for the URL in the database.
      if(array_key_exists("ETag", $headers)){
				//SQL to update the URL in both the urls and user_url_list tables.
				//Only update urls if this is the only user with this url.
				//Otherwise, insert.
				$edit_sql_urls = "INSERT INTO urls (url) VALUES ('$editedURL')";
				$get_user_url_list_info = "SELECT * FROM user_url_list WHERE url='$_GET[edit_id]' ";
				$run_get_info = mysqli_query($conn , $get_user_url_list_info);
				$count = mysqli_num_rows($run_get_info);
				if($count == 1){
					$edit_sql_urls = "UPDATE urls SET url = '$editedURL' WHERE url='$_GET[edit_id]' ";
				}
				$edit_sql_user_url_list = "UPDATE user_url_list SET url = '$editedURL' WHERE email='$Email' AND url='$_GET[edit_id]'";
				//Run the previous two queries.
				$run_sql_insert_url = mysqli_query($conn , $edit_sql_urls);
				$run_sql_insert_user_url_list = mysqli_query($conn , $edit_sql_user_url_list);
				//Update ETag for the new URL.
				$sql_update = "UPDATE urls SET etag = '$headers[ETag]' WHERE url = '$editedURL'";
        $execute_update = mysqli_query($conn,$sql_update);
        /*if($execute_update){
          echo "Updated $editedURL ETag.";
          echo "</br>";
        }*/
      }
		}
		//If the URL was updated in the tables and wasn't deleted later,
		//go back to/refresh the homepage so that it shows up in the table.
		if($run_sql_insert_user_url_list){
			echo "<script>window.location = \"/NotifyMe/userHomepage.php\"; </script>";
		}
		//Otherwise, tell the user that we were unable to add the URL.
		else{
			?>
			<script>alert("URL was not added!\n\nSorry, this URL does not have a Last-Modified value or an Etag value.")</script>
			<?php
		}
	}

	//If the user wants to delete the URL, delete it from the database AND
	//refresh the homepage.
	if(isset($_GET['del_id'])){
		//SQL to delete the del_id url from the urls and user_url_list tables.
		$del_sql = "DELETE FROM urls WHERE url = '$_GET[del_id]'";
		$del_sql_user_url_list = "DELETE FROM user_url_list WHERE (url= '$_GET[del_id]' AND email= '$Email')";

		//Only delete from urls if it is the only one.
		$get_user_url_list_info = "SELECT * FROM user_url_list WHERE url='$_GET[del_id]' ";
		$run_get_info = mysqli_query($conn , $get_user_url_list_info);
		$count = mysqli_num_rows($run_get_info);
		if($count == 1){
			//Run the sql statement to delete from urls.
			$run_del_sql = mysqli_query($conn , $del_sql);
		}
		//Run the sql statement to delete from user_url_list where email is user's email.
		$run_del_sql_user_url_list = mysqli_query($conn , $del_sql_user_url_list);

		if($run_del_sql_user_url_list){
				echo "<script>window.location = \"/NotifyMe/userHomepage.php\"; </script>";
		}
	}
?>
