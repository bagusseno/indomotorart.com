<?php

  require_once($_SERVER['DOCUMENT_ROOT'] . "/function/database.php");
  require_once($_SERVER['DOCUMENT_ROOT'] . "/function/account.php");
  require_once($_SERVER['DOCUMENT_ROOT'] . "/function/session.php");

  if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if(!empty($username) && !empty($password)) {
      $loggedIn = login($username, $password);
      if($loggedIn == "loggedin") {
        echo 'already';
        die();
      }
      if($loggedIn == "notfound") {
        echo 'notfound';
        die();
      }
      if($loggedIn) {
        echo 'loggedin';
      }
    }

  }
  
?>
