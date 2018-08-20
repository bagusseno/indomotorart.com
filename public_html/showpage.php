<?php
// including important files
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/session.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/database.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/account.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/asset.php');
// redirect if no parameter found
if(!isset($_GET['id'])) {
  header("Location: $HOME_URL");
}
// getting parameter
$pageID = $_GET['id'];
// get data and check the existence of the given page ID
$page = $conn->query("SELECT * FROM page WHERE ID='$pageID'");
if($page->num_rows <=0) {
  $error = '<h1>Halaman tidak ditemukan.</h1>
  <a href="' . $HOME_URL . '">Kembali ke Beranda</a>
';
} else {
  $page = $page->fetch_array(MYSQLI_ASSOC);
  $title = $page["title"];
  $content = nl2br($page["content"]);
  $by = get_user_full_nick($page['userID']);
  $WEB_DESC = $content;
}

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title><?php echo $title ?> - <?php echo $WEB_NAME ?></title>
  </head>
  <body style="height:unset">

    <?php

    require_once($_SERVER['DOCUMENT_ROOT'] . '/parts/top/top-header-2.php');

    ?>
    <div class="full-wrapper" id="main">
      <div class="container-1040">
        <?php if(!isset($error)) { ?>
        <div class="cat-title">
          <h1><?php echo $title ?></h1>
          <small>Oleh <?php echo $by ?></small>
        </div>
        <div class="content">
          <p><?php echo $content ?></p>
        </div>
        <?php
      } else {
        echo $error;
      }
        ?>
      </div>
    </div>
    <?php
    require_once($_SERVER['DOCUMENT_ROOT'] . '/parts/bottom.php');
    ?>
  </body>
</html>
