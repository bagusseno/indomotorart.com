<?php

// Admin Area
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/account.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/database.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/asset.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/session.php');
require_once('functions.php');

if(!is_user_logged_in()) {
  header("Location: $HOME_URL");
} else {
  $userID = $_SESSION['user'];
  $username = $conn->query("SELECT username FROM user WHERE ID='$userID'")->fetch_assoc()['username'];
  if($username != "bagusseno") {
    header("Location: $HOME_URL");
  }
}

if(isset($_GET['id'])) {
  $pageID = $_GET['id'];
}

$msg = "";
if(isset($_POST['editpage'])) {

  if(!empty($_POST['title']) && !empty($_POST['article'])) {
    $title = $_POST['title'];
    $article = $_POST['article'];

    $update = $conn->query("UPDATE page SET title='$title', content='$article' WHERE ID='$pageID'");
  }
}

$title = get_page_meta("title", $pageID);
$content = get_page_meta("content", $pageID);
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
          <h1>Edit Page for <?php echo get_page_meta("title", $pageID) ?></h1>
        </div>
        <div class="db-content">
          <h2><?php echo $msg ?></h2>
          <form action="" method="POST" id="page-form">
            <input type="text" name="title" id="title" placeholder="Page Title.." value="<?php echo $title ?>"/>
            <textarea name="article" id="article"><?php echo $content ?></textarea>
            <br><br>
            <input type="hidden" name="editpage" class="btn"/>
            <input type="submit" value="Update" class="btn"/>
          </form>
        </div>
      </div>

    </div>

  </body>
  <link rel="stylesheet" type="text/css" href="dash-style.css"/>
</html>
