<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/function/database.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/function/account.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/function/session.php");

if(isset($_POST['postID'])) {
  $postID = $_POST['postID'];
  if(get_user_meta("username") == "bagusseno") {
    $delete = $conn->query("DELETE FROM post WHERE ID='$postID'");
    if($delete){
      echo 'success';
    } else {
      echo 'fail';
    }
  } else {
    echo 'noaccess';
  }
}

 ?>
