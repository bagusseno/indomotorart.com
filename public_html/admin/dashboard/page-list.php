<?php

// Admin Area
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/account.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/database.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/asset.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/session.php');

if(!is_user_logged_in()) {
  header("Location: $HOME_URL");
  exit;
} else {
  $userID = $_SESSION['user'];
  $username = $conn->query("SELECT username FROM user WHERE ID='$userID'")->fetch_assoc()['username'];
  if($username != "bagusseno") {
    header("Location: $HOME_URL");
    exit;
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
          <h1>Page List</h1>
        </div>
        <div class="db-content">
          <table width=100%>
            <tr>
              <th>ID</th>
              <th>Title</th>
              <th>Author</th>
              <th>Actions</th>
            </tr>
          <?php

          $pages = $conn->query("SELECT * FROM page");

          if($pages->num_rows > 0) {

            $pages = $pages->fetch_all(MYSQLI_ASSOC);
            foreach($pages as $page) {
              $ID = $page['ID'];
              $title = $page['title'];
              $author = get_user_meta("username", $ID);
              echo "<tr><td>$ID</td><td>$title</td><td>$author</td><td><a href='$HOME_URL/admin/dashboard/edit-page.php?id=$ID'>Edit</a> | <a href='$HOME_URL/admin/dashboard/delete-page.php?id=$ID'>Delete</a></td>";
            }
          }

          ?>
        </table>
        </div>
      </div>

    </div>

  </body>
  <link rel="stylesheet" type="text/css" href="dash-style.css"/>
  <style>
  th, td {
    border: 1px solid;
    padding: 10px;
  }
  </style>
</html>
