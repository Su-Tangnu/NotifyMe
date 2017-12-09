<?php
  $conn = mysqli_connect("localhost","root","","notifyme_db");
  $sql_urls_info = "SELECT * FROM urls";
  $execute_urls_info = mysqli_query($conn,$sql_urls_info);
  if($execute_urls_info){
    while($data = mysqli_fetch_assoc($execute_urls_info)){
      $properURL = $data['url'];
      if((substr($properURL,0,4)!='HTTP')&&(substr($properURL,0,4)!='http')&&(substr($properURL,0,4)!='HTTPS')&&(substr($properURL,0,4)!='https')){
        $properURL = "http://" . $properURL;
      }
      if($headers = get_headers($properURL, 1)){
        if(array_key_exists("Last-Modified", $headers) && ($headers["Last-Modified"] == $data["lastModified"])){
          $lastModified = "Last-Modified";
          $sql_update = "UPDATE urls SET updated = '0' WHERE url = '$data[url]'";
          $execute_update = mysqli_query($conn,$sql_update);
          if($execute_update){
            echo "$data[url] not updated based on Last-Modified.";
            echo "</br>";
          }
        }
        elseif (array_key_exists("ETag", $headers) && ($headers["ETag"] == $data["etag"])) {
          $sql_update = "UPDATE urls SET updated = '0' WHERE url = '$data[url]'";
          $execute_update = mysqli_query($conn,$sql_update);
          if($execute_update){
            echo "$data[url] not updated based on Etag.";
            echo "</br>";
          }
        }
        elseif (array_key_exists("Last-Modified", $headers)){
          $lastModified = "Last-Modified";
          $sql_update = "UPDATE urls SET lastModified = '$headers[$lastModified]', updated = '1' WHERE url = '$data[url]'";
          //$sql_update = "UPDATE urls SET updated = '1' WHERE url = '$data[url]'";
          $execute_update = mysqli_query($conn,$sql_update);
          if($execute_update){
            echo "Updated $data[url] based on Last-Modified.";
            echo "</br>";
          }
        }
        elseif(array_key_exists("ETag", $headers)){
          $sql_update = "UPDATE urls SET etag = '$headers[ETag]', updated = '1' WHERE url = '$data[url]'";
          $execute_update = mysqli_query($conn,$sql_update);
          if($execute_update){
            echo "Updated $data[url] based on ETag.";
            echo "</br>";
          }
        }
      }
      else{
        echo "Not a proper URL.";
        echo "</br>";
      }
    }
  }

 /* $sql_users_to_email = "SELECT users.email
                         FROM users
                         INNER JOIN user_url_list ON users.email = user_url_list.email
                         INNER JOIN urls ON urls.url = user_url_list.url
                         WHERE urls.updated = 1";*/
  /*$sql_users_to_email = "SELECT user_url_list.email
                         FROM user_url_list
                         INNER JOIN urls ON urls.url = user_url_list.url
                         WHERE urls.updated = 1";*/
  $sql_users_to_email = "SELECT *
                         FROM user_url_list , urls
                         WHERE  urls.url = user_url_list.url
                         AND urls.updated = 1";

  $execute_users_to_email = mysqli_query($conn,$sql_users_to_email);
  if($execute_users_to_email){
    while($emails_url = mysqli_fetch_assoc($execute_users_to_email)){
      // the message
      $msg = $emails_url['url'];
      // use wordwrap() if lines are longer than 70 characters
      $msg = wordwrap($msg,70);
      // send email
      $sent = mail($emails_url['email'],"Websites Have Updated!",$msg);
      echo "Email Sent to ". $emails_url['email'] . " about " . $emails_url['url'];
      echo "</br>";
    }
  }
?>
