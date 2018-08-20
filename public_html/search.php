<?php
// including important files
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/session.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/database.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/account.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/asset.php');
// redirect if no parameter found
if(isset($_GET['keyword']) || isset($_GET['tag'])) {
} else {
  header("Location: $HOME_URL");
}
// getting parameter
$found = 0;
if(isset($_GET['keyword'])) {
  $kind = "search";
  $keyword = $_GET['keyword'];
  $title = "Search for $keyword";
} elseif(isset($_GET['tag'])) {
  $kind = "tag";
  $keyword = $_GET['tag'];
  $title = "Search tag $keyword";
}
// get data and check the existence of the given page ID
if($kind == "search") {
$content = "<div>Search for $keyword tidak ditemukan<br><a href='$HOME_URL'>Kembali ke Beranda</a></div>";  
} elseif($kind == "tag") {
$content = "<div>Search for tag: $keyword tidak ditemukan<br><a href='$HOME_URL'>Kembali ke Beranda</a></div>";
}
if(is_user_logged_in()) {
  $is_user_logged_in = 1;
} else {
  $is_user_logged_in = 0;
}

if(get_user_meta('token') != "activated") {
  $is_user_activated = 0;
} else {
  $is_user_activated = 1;
}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title><?php echo strip_tags($title) ?> - <?php echo $WEB_NAME ?></title>
  </head>
  <body style="height:unset">
      <style>
          .outcontent {
              margin-bottom: 15px;
          }
      </style>

    <?php

    require_once($_SERVER['DOCUMENT_ROOT'] . '/parts/top/top-header-2.php');

    ?>
    <div class="full-wrapper" id="main">
      <div class="container">
        <div class="cat-title">
          <h1><?php echo $title ?></h1>
        </div>
        <div class="outcontent">
            <form id="search-form" method="get" action="http://indomotorart.com/search.php">
                <input type="text" id="search-input" name="keyword" placeholder="Cari sesuatu...">
                <button type="submit" id="search-submit"><a class="fa fa-search"></a></button>
            </form>
        </div>
        <div class="post-list" id="content">
        
        </div>
      </div>
    </div>
    <?php
    require_once('parts/bottom.php');
    ?>
  </body>
  <script type="text/javascript" src="http://indomotorart.com/function/post-feed.js"></script>
  <script type="text/javascript">
    showPost("all", 0, "content", "<?php echo $content ?>", 0, null, "<?php echo $kind ?>", "<?php echo $keyword ?>");
    $(document).on('click', '.love-post', function() {
    console.log("Clicked");
    var postID = $(this).attr('data-loveid');
    var thisx = $(this);
    var loggedin = <?php echo $is_user_logged_in ?>;
    var activated = <?php echo $is_user_activated ?>;

    console.log(postID);
    if(loggedin == 0) {
      window.location.replace("http://indomotorart.com/login");
    } else if(activated == 0) {
      window.location.replace("http://indomotorart.com/akun/verifikasi");
    } else {
        $.ajax({
          type: "POST",
          url: "http://indomotorart.com/function/vote.php",
          dataType: "JSON",
          data: {postID : postID},
          success: function(r) {
            console.log(r);
            if(r == "unlogged") {
              window.location.replace("http://indomotorart.com/login");
            }
            if(r["updown"] == '1') {
              var total= r["total"];
              thisx.addClass("loved fa-heart");
              thisx.removeClass("fa-heart-o");
              thisx.html("" + total);
            } else if (r["updown"] == '-1') {
              var total= r["total"];
              thisx.removeClass("loved fa-heart");
              thisx.addClass("fa-heart-o");
              thisx.html("" + total);
            } else {
              alert("terjadi error");
            }
          }
        })
    }
  })
  $(document).on("dblclick", ".post-item", function() {
    console.log("ASDS")
    $(this).children('.bottom-post').children('.love-post').click();
    console.log("dblclicked");
  });
  </script>
</html>
