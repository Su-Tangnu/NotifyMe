<?php
  $conn = mysqli_connect("localhost","root","","notifyme_db");
  $sql_urls_info = "SELECT * FROM urls";
  $execute_urls_info = mysqli_query($conn,$sql_urls_info);
  if($execute_urls_info){
    while($data = mysqli_fetch_assoc($execute_urls_info)){
      $headers = get_headers($data['url'], 1);
      if(array_key_exists("Last-Modified", $headers) && ($headers["Last-Modified"] == $data["last-modified"])){
        $sql_update = "UPDATE urls SET updated = '0' WHERE url = $data[url]";
        $execute_update = mysqli_query($conn,$sql_update);
        if($execute_update){
          echo "Not updated based on Last-Modified.";
        }
      }
      elseif (array_key_exists("ETag", $headers) && ($headers["ETag"] == $data["etag"])) {
        $sql_update = "UPDATE urls SET updated = '0' WHERE url = $data[url]";
        $execute_update = mysqli_query($conn,$sql_update);
        if($execute_update){
          echo "Not updated based on Etag.";
        }
      }
      elseif (array_key_exists("Last-Modified", $headers)){
        $lastModified = "Last-Modified";
        $sql_update = "UPDATE urls SET last-modified = '$headers[$lastModified]', updated = '1' WHERE url = $data[url]";
        $execute_update = mysqli_query($conn,$sql_update);
        if($execute_update){
          echo "Updated $data[url] based on Last-Modified.";
        }
      }
      elseif(array_key_exists("ETag", $headers)){
        $sql_update = "UPDATE urls SET etag = '$headers[ETag]', updated = '1' WHERE url = $data[url]";
        $execute_update = mysqli_query($conn,$sql_update);
        if($execute_update){
          echo "Updated $data[url] based on ETag.";
        }
      }
    }
  }

 /* $sql_users_to_email = "SELECT users.email
                         FROM users
                         INNER JOIN user_url_list ON users.email = user_url_list.email
                         INNER JOIN urls ON urls.url = user_url_list.url
                         WHERE urls.updated = 1";*/
  $sql_users_to_email = "SELECT user_url_list.email
                         FROM user_url_list
                         INNER JOIN urls ON urls.url = user_url_list.url
                         WHERE urls.updated = 1";
  $execute_users_to_email = mysqli_query($conn,$sql_users_to_email);
  if($execute_users_to_email){
    while($emails = mysqli_fetch_assoc($execute_users_to_email)){
      // the message
      $msg = "You have websites that have updated!\nCome see which ones at NotifyMe!";
      // use wordwrap() if lines are longer than 70 characters
      $msg = wordwrap($msg,70);
      // send email
      $sent = mail($emails['email'],"Websites Have Updated!",$msg);
    }
  }
?>
