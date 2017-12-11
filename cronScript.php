<?php
  //mysqli_connect(server,username,password,database)
  $conn = mysqli_connect("localhost","root","","notifyme_db");
  //Get all of the URLs so that we can check them.
  $sql_urls_info = "SELECT * FROM urls";
  //Run the previous SQL statement.
  $execute_urls_info = mysqli_query($conn,$sql_urls_info);
  //If we got any URLs from the query, check and see if they've updated.
  if($execute_urls_info){
    //Loop over the URLs per row using an associative array.
    while($data = mysqli_fetch_assoc($execute_urls_info)){
      //properURL is the url, but it ensures that "http://" is in front of it
      //so that the headers are properly retrieved.
      $properURL = $data['url'];
      //If $properURL does not have http in front, add "http://".
      if((substr($properURL,0,4)!='HTTP')&&(substr($properURL,0,4)!='http')&&(substr($properURL,0,4)!='HTTPS')&&(substr($properURL,0,4)!='https')){
        $properURL = "http://" . $properURL;
      }
      //Get the headers as an associative array and check if Last-Modified
      //or ETag has changed compared to what is in the database.
      //If it has, flip the updated flag.  Otherwise, set updated to 0/false.
      if($headers = get_headers($properURL, 1)){
        //If Last-Modified exists and is the same as what is in the database,
        //set updated to 0.
        if(array_key_exists("Last-Modified", $headers) && ($headers["Last-Modified"] == $data["lastModified"])){
          //SQL Update statement.
          $sql_update = "UPDATE urls SET updated = '0' WHERE url = '$data[url]'";
          //Run SQL update statement.
          $execute_update = mysqli_query($conn,$sql_update);
          //If the update succeeded, print the message as to what happened.
          if($execute_update){
            echo "$data[url] not updated based on Last-Modified.";
            echo "</br>";
          }
        }
        //If ETag exists and is the same as what is in the database,
        //set updated to 0.
        elseif (array_key_exists("ETag", $headers) && ($headers["ETag"] == $data["etag"])) {
          //SQL Update statement.
          $sql_update = "UPDATE urls SET updated = '0' WHERE url = '$data[url]'";
          //Run SQL update statement.
          $execute_update = mysqli_query($conn,$sql_update);
          //If the update succeeded, print the message as to what happened.
          if($execute_update){
            echo "$data[url] not updated based on Etag.";
            echo "</br>";
          }
        }
        //If Last-Modified exists and is not the same as what is in the database,
        //set updated to 1 and update Last-Modified in the database.
        elseif (array_key_exists("Last-Modified", $headers)){
          //SQL statement does not like dashes, so mask it in a variable.
          $lastModified = "Last-Modified";
          //SQL Update statement.
          $sql_update = "UPDATE urls SET lastModified = '$headers[$lastModified]', updated = '1' WHERE url = '$data[url]'";
          //Run SQL update statement.
          $execute_update = mysqli_query($conn,$sql_update);
          //If the update succeeded, print the message as to what happened.
          if($execute_update){
            echo "Updated $data[url] based on Last-Modified.";
            echo "</br>";
          }
        }
        //If ETag exists and is not the same as what is in the database,
        //set updated to 1 and update ETag in the database.
        elseif(array_key_exists("ETag", $headers)){
          //SQL Update statement.
          $sql_update = "UPDATE urls SET etag = '$headers[ETag]', updated = '1' WHERE url = '$data[url]'";
          //Run SQL update statement.
          $execute_update = mysqli_query($conn,$sql_update);
          //If the update succeeded, print the message as to what happened.
          if($execute_update){
            echo "Updated $data[url] based on ETag.";
            echo "</br>";
          }
        }
      }
      //Could not get the headers, so it wasn't a proper URL.
      else{
        echo "Not a proper URL.";
        echo "</br>";
      }
    }
  }

  //Get all information from the JOIN of user_url_list and urls
  //where the url has updated. (The url equivalence just means
  //that the we get the data for the same url from both tables.)
  $sql_users_to_email = "SELECT *
                         FROM user_url_list , urls
                         WHERE  urls.url = user_url_list.url
                         AND urls.updated = 1";

  //Execute the previous SQL statement.
  $execute_users_to_email = mysqli_query($conn,$sql_users_to_email);

  //If we got any data from the statement,
  //URLs have updated, so we need to send emails.
  if($execute_users_to_email){
    //Loop over the results as an associative array.
    while($emails_url = mysqli_fetch_assoc($execute_users_to_email)){
      //The message will be the url that has updated.
      $msg = $emails_url['url'];

      //Send email with header "Websites Have Updated!"
      $sent = mail($emails_url['email'],"Websites Have Updated!",$msg);
      
      //Let the operator know that the email was sent.
      echo "Email Sent to ". $emails_url['email'] . " about " . $emails_url['url'];
      echo "</br>";
    }
  }
?>
