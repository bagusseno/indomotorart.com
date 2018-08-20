<?php

// Admin Area
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/account.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/database.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/asset.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/session.php');

if(!is_user_logged_in()) {
  header("Location: $HOME_URL");
} else {
  $userID = $_SESSION['user'];
  $username = $conn->query("SELECT username FROM user WHERE ID='$userID'")->fetch_assoc()['username'];
  if($username != "bagusseno") {
    header("Location: $HOME_URL");
  }
}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Dashboard - <?php echo $WEB_NAME ?></title>
  </head>
  <body>

    <div class="container-db">

      <?php require_once("menubar.php"); ?>

      <div id="content">
        <div class="cat-title">
          <h1>Dashboard</h1>
        </div>

      </div>

    </div>

  </body>
  <link rel="stylesheet" type="text/css" href="dash-style.css"/>

</html>
