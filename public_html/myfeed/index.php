<?php
// including important files
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/session.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/database.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/account.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/asset.php');
// get user ID
$userID = $_SESSION['user'];
// declare content
$content = "<div>Nantinya di sini ditampilkan foto-foto dari berbagai jenis motor yang kamu follow. <a href='http://indomotorart.com/myfeed/follow'>Follow Sekarang</a></div><style>.post-list{display:block}</style>";
if(!isset($_SESSION['user'])) {
    header("Location: http://indomotorart.com");
    exit;
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
    <title>My Feed - <?php echo $WEB_NAME ?></title>
  </head>
  <body style="height:unset">

    <?php
    
    require_once($_SERVER['DOCUMENT_ROOT'] . '/parts/top/top-header-2.php');

    ?>
    <div class="full-wrapper" id="main">
      <div class="container">
        <div class="cat-title">
          <h1 style="display:inline-block">My Feed</h1>
          <a href="http://indomotorart.com/myfeed/follow">follow yang lain <i class="fa fa-external-link"></i></a>
        </div>
        <div class="outcontent">
        </div>
        <div class="post-list" id="feed">
        </div>
      </div>
    </div>
    <?php
    require_once($_SERVER['DOCUMENT_ROOT'] . '/parts/bottom.php');
        if($_GET["status"] == "new") {
            require_once $_SERVER['DOCUMENT_ROOT'] . "/parts/popup.php";
        }
    ?>
  </body>
    <script type="text/javascript" src="http://indomotorart.com/function/post-feed.js"></script>
    <script type="text/javascript">
    showPost("feed", 0, "feed", "<?php echo $content ?>", 0, null, null);
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
