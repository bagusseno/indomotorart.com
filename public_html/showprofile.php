<?php
// including important files
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/session.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/database.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/account.php');
// detect is user logged in or not to handle voting system
if(!is_user_logged_in()) {
  $is_user_logged_in = 0;
} else {
  $is_user_logged_in = 1;
}
// detect whether user is activated or not
if(get_user_meta('token') != "activated") {
  $is_user_activated = 0;
} else {
  $is_user_activated = 1;
}
// if there is no given parameter, better to redirect it
if(!isset($_GET['user'])) {
  header("Location: $HOME_URL");
}
// getting data to be stored into variables from user
$userUrl = $_GET['user'];
// get ID from database
$userData = $conn->query("SELECT * FROM user WHERE url='$userUrl'");
// Checking the existence of the user by given user url
if($userData->num_rows <= 0) {
  $error = "nouserfound";
} else {
  $error = "";
  // storing variables
  // fetching the data
  $userData = $userData->fetch_array(MYSQLI_ASSOC);
  $userID = $userData['ID'];
  $username = $userData["username"];
  $nickname = get_user_nick($userData['ID']);
  $title = get_user_title($userData["ID"]);
  $avatarUrl = $userData["avatar"];
  $brand = ucfirst($userData["brand"]);
  $tipe = $userData["tipe"];
  $kota = $userData["kota"];
  $daerah = $userData["daerah"];
  $totalPoint = $userData["total_point"];
  $joinDate = $userData["join_date"];
  // set full address
  if($daerah != "") {
    $fullAdress = $daerah;
  }
  elseif($kota != "") {
    $fullAdress = $kota;
  }
  elseif($daerah != "" && $kota != "") {
    $fullAdress = $daerah . ", " . $kota;
  }
  else {
    $fullAdress = "-";
  }
  // if data is null, make it looks like ""
  if($title == null) $title = "";
  if($avatarUrl == null) $avatarUrl = "";
  if($brand == null) $brand = "";
  if($tipe == null) $tipe = "";
  if($kota == null) $kota = "";
  if($daerah == null) $daerah = "";
}
$WEB_DESC = "Berikut ini menampilkan profile dengan username $username dan nickname $nickname yang berada
              di IndoMotorART.";
?>
<!DOCTYPE html>
<html style="height:100%">
  <head>
    <meta charset="utf-8">
    <title><?php echo $nickname . " - " . $WEB_NAME ?></title>
    <meta name="description" content="<?php echo $WEB_DESC ?>"/>
  </head>
  <body>

    <?php
    require_once($_SERVER['DOCUMENT_ROOT'] . '/asset.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/parts/top/top-header-2.php');
    ?>
    <div class="full-wrapper" id="main">

      <div class="container">
        <?php if($error == "") { ?>
        <div class="userinfo">
          <div class="useravatar">
            <?php echo get_user_avatar($userID, 100, 100) ?>
          </div>
          <div class="userdetails-single">
            <h1><?php echo $nickname ?> (<?php echo $title ?>)</h1>
            <p class="usermotor"><?php echo $brand . " " . $tipe ?></p>
            <p class="userdate">Bergabung sejak: <?php echo $joinDate ?></p>
            <p class="userdate">Tinggal di: <?php echo $fullAdress ?></p>
          </div>
        </div>
        <div class="cat-title">
          <h2>Foto oleh <?php echo $nickname ?></h2>
        </div>
        <div id="userposts" class="post-list">
          Loading...
        </div>
        <div id="msg-endpost">
        </div>
        <?php
        }
        // but there is no user found...
        elseif($error == "nouserfound") {
          echo "<h1>User tidak ditemukan.</h1>";
        } else {
          echo "<h1>Terjadi error.</h1>";
        }
        ?>
      </div>

    </div>

    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/parts/bottom.php'); ?>

  </body>
  <script type="text/javascript" src="<?php echo $HOME_URL ?>/function/post-feed.js"></script>
  <script type="text/javascript">
  $(document).ready(function() {

    // below is specialized only for user with autoload system
    var userID = <?php echo $userID ?>;
    var newStart = 0;
    var newLimit = 6;
    var newStarted = 0;
    var newMsg = "Maaf! Tidak ditemukan foto oleh <?php echo $nickname ?>.";
    var is_user_logged_in = <?php echo $is_user_logged_in ?>;

    $("#userposts").html("");

    showPost(userID, newLimit, "userposts", newMsg, newStart, null, "user");
    newStart += newLimit;
    $(window).scroll(function() {
      console.log(outRes);
        if(newStarted == 0) {
          if($(window).scrollTop() > $("html").height() - window.innerHeight - 50) {
            newStarted = 1;
            console.log("triggered");
          }
        }

        if(newStarted == 1) {
          if(outRes != 0) {
            showPost(userID, newLimit, "userposts", "Anda telah mencapai dasar. Tidak ditemukan lagi foto yang lebih lama.", newStart, "msg-endpost", "user");
          }
          newStart += newLimit;
          newStarted = 0;
        }

    })
  })

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
  <style type="text/css">

  .useravatar {
    float: left;
    margin-right: 20px;
  }
  .userdetails-single {
    display: inline-block;
  }
  .userinfo {
    display: inline-block;
    border-bottom: 1px solid #ccc;
    padding-bottom: 25px;
    width: 100%;
  }
  .cat-title {
    margin-top: 25px;
  }
  #footer {
      position:unset!important;
  }
  .full-wrapper {
  }
  </style>
</html>
