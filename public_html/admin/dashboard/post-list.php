<?php

// Admin Area
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/account.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/database.php');
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
    <title>Post List - <?php echo $WEB_NAME ?></title>
  </head>
  <body>

    <div class="container-db">

      <?php require_once("menubar.php"); ?>

      <div id="content">
        <div class="cat-title">
          <h1>Post List</h1>
        </div>
        <div id="postlist" class="db-content post-list">

        </div>
      </div>

    </div>
    <?php
    require_once($_SERVER['DOCUMENT_ROOT'] . '/asset.php');
    ?>
  </body>
  <link rel="stylesheet" type="text/css" href="dash-style.css"/>
  <script type="text/javascript" src="http://indomotorart.com/function/post-feed.js"></script>
  <script>
  showPost("all", 0, "postlist", "Maaf! Tidak ditemukan foto apapun.", 0, null, "admin");
  $(document).on("click", ".delete-post-btn", function() {
    console.log("sad");
    thisx = $(this);
    postID = $(this).attr("ID");
    $.ajax({
      url: "http://indomotorart.com/function/deletePost.php",
      type: "POST",
      data: {"postID" : postID},
      success: function(r) {
        console.log("Delete" + postID);
        if(r == "success") {
          thisx.parent().parent().parent().parent().remove();
          alert("success");
        }
        if(r == "fail") {
          alert("fail");
        }
        if(r == "noaccess") {
          alert("You have no access");
        }
      }
    })
  })
  </script>
</html>
