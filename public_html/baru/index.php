<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/session.php');
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>IndoMotorStyle - Indonesia Motorcycle Art Style</title>
  </head>
  <body>

    <?php

    // website system
    require_once($_SERVER['DOCUMENT_ROOT'] . '/function/database.php');

    // website structures
    require_once($_SERVER['DOCUMENT_ROOT'] . '/parts/top.php');

    ?>

    <div class="full-wrapper" id="main">

      <div class="container">

        <div class="cat-title">
          <h2>Foto Baru</h2>
        </div>
        <div class="post-list" id="new">
          Loading...
        </div>
        <div id="msg-endpost">
        </div>
      </div>

    </div>

    <?php
    require_once($_SERVER['DOCUMENT_ROOT'] . '/parts/bottom.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/asset.php');

    ?>
  </body>

<?php

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
<script type="text/javascript" src="http://indomotorart.com/function/post-feed.js"></script>
<script type="text/javascript">
  // from StackOverFlow by Majid Golshadi https://stackoverflow.com/users/2603921/majid-golshadi the link:https://stackoverflow.com/a/20819663
  $(document).ready(function() {

    // below is specialized only for new with autoload system
    <?php
    if(isset($_GET['post'])) {
    ?>
    var newStart = <?php echo $_GET['post']; ?>;
    <?php } else { ?>
    var newStart = 0;
    <?php } ?>    var newLimit = 6;
    var newStarted = 0;
    var newMsg;
    var is_user_logged_in = <?php echo $is_user_logged_in ?>;
    var printed = 0;
    var reachesPageLimit = 0;

    newMsg = "Selamat! Kamu telah mencapai dasar! Tidak ditemukan lagi foto yang lebih lama.";

    function showMsgPageLimit() {
      console.log("ASDSADaMMM");
      reachesPageLimit = 1;
      if(reachesPageLimit == 1) {
        $("#msg-endpost").append("<a href='<?php echo $HOME_URL ?>/baru?post=" + (newStart) + "'>lihat yang lebih lama -></a>");
        // how to change the display type on FadeIn learned from https://stackoverflow.com/a/10322651 by MakuraYami (https://stackoverflow.com/users/1235305/makurayami)
        $("#msg-endpost").fadeIn().css("display", "table");
        reachesPageLimit = 2;
      }
    }

    <?php
    // to control the pagination system
    if(!isset($_GET['post'])) {
      ?>

    $("#new").html("");
    showPost("new", newLimit, "new", "Maaf! Tidak ditemukan foto baru.", newStart);
    newStart += newLimit;
    printed += newLimit;
    $(window).scroll(function() {
      console.log(outRes);
        if(newStarted == 0) {
          if($(window).scrollTop() > $("html").height() - window.innerHeight - 350) {
            setTimeout(function() {
              newStarted = 1;
              console.log("triggered");
            }, 300);
          }
        }

        if(newStarted == 1) {
          if(outRes != 0 && printed < 20) {
            showPost("new", newLimit, "new", newMsg, newStart, "msg-endpost");
            printed += newLimit;
          }
          if(outRes != 0 && printed >= 20) {
            if(reachesPageLimit == 0) {
              showMsgPageLimit();
            }
          }
          newStart += newLimit;
          newStarted = 0;
        }

    })
  })
  <?php } else { ?>
    $("#new").html("");
    showPost("new", newLimit, "new", "Maaf! Tidak ditemukan foto baru.", newStart);
    newStart += newLimit;
    printed += newLimit;
    $(window).scroll(function() {
      console.log(outRes);
        if(newStarted == 0) {
          if($(window).scrollTop() > $("html").height() - window.innerHeight - 350) {
            setTimeout(function() {
              newStarted = 1;
              console.log("triggered");
            }, 300);
          }
        }

        if(newStarted == 1) {
          if(outRes != 0 && printed < 20) {
            showPost("new", newLimit, "new", newMsg, newStart, "msg-endpost");
            printed += newLimit;
          }
          if(outRes != 0 && printed >= 20) {
            if(reachesPageLimit == 0) {
              showMsgPageLimit();
            }
          }
          newStart += newLimit;
          newStarted = 0;
        }

    })
  });
  <?php } ?>
  // from StackOverFlow by Majid Golshadi https://stackoverflow.com/users/2603921/majid-golshadi the link:https://stackoverflow.com/a/20819663
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
