<?php

require_once 'exceptionHandler.php';

function auth($username, $password) {
  // validating
  if(!isset($username)) {
    $isError = true;
    echo $err["API/auth/noUser"];
    die();
  }
  if(!isset($password)) {
    $isError = true;
    echo $err["API/auth/noPass"];
    die();
  }

  // authenticating
  $authed = false;

  if($username == "admin" && $password == "admin") {
    return true;
  } else {
    return $err["API/auth/invalid"];
  }
}

function escape($con, $string) {
  return mysqli_real_escape_string($con, $string);
}
?>
