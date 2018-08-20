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
// detect active
if(get_user_meta('token') != "activated") {
  $is_user_activated = 0;
} else {
  $is_user_activated = 1;
}
// if there is no given parameter, better to redirect it
if(!isset($_GET['brand'])) {
  header("Location: $HOME_URL");
  exit;
}

// getting data to be stored into variables from user
$brandUrl = strtolower(mysqli_real_escape_string($conn, $_GET['brand']));
$brandUrl = str_replace("-", " ", $brandUrl);
$brandUrl = str_replace("/", "", $brandUrl);

$checkBrand = $conn->query("SELECT ID FROM brand WHERE name='$brandUrl'");
// check existence 
if($checkBrand->num_rows > 0) {
    $brandExist = 1;
    $brand = ucfirst($brandUrl);
    $postType = "";
    if(!isset($_GET["type"])) {
        $tipeList = $conn->query("SELECT name FROM tipe WHERE brandID='$brandUrl'");
        $tipeList = $tipeList->fetch_all(MYSQLI_ASSOC);
        $postInfo = $conn->query("SELECT ID FROM post WHERE brand='$brandUrl'");
        $lastDate = $conn->query("SELECT date FROM post WHERE brand='$brandUrl' LIMIT 1");
        // get total post
        $totalPost = $postInfo->num_rows;
        // get last date
        $lastDate = $lastDate->fetch_assoc()["date"]; 
        $title = strtolower(mysqli_real_escape_string($conn, $_GET['brand']));
        $title = str_replace("-", " ", $title);
        $postType = "brand";
        // set type
        $type = "brand";
    } else {
        $typeUrl = mysqli_real_escape_string($conn, $_GET["type"]);
        $typeUrl = str_replace("-", " ", $typeUrl);
        $typeUrl = str_replace("/", "", $typeUrl);
        // check the existence first
        $getType = $conn->query("SELECT name,brandID FROM tipe WHERE name='$typeUrl' || url='$typeUrl'");
        if($getType->num_rows <= 0) {
            $typeExist = 0;
        } else {
            $typeExist = 1;
        }
        $typeName = $getType->fetch_assoc()["name"];
        $getPosts = $conn->query("SELECT title FROM post WHERE tipe='$typeName'");
        $lastDate = $conn->query("SELECT date FROM post WHERE tipe='$typeUrl' LIMIT 1");
        // get total post
        $totalPost = $getPosts->num_rows;
        // get last date
        $lastDate = $lastDate->fetch_assoc()["date"]; 
        $title = $brand . " " . $typeName;
        $postType = "tipe";
        // set type
        $type = "tipe";
    }
    $title = str_replace("/", "", $title);
    
} else {
    $brandExist = 0;
}
$WEB_DESC = "Berikut ini menampilkan foto-foto motor $brand $title yang telah diupload di IndoMotorART. IndoMotorART sendiri adalah website komunitas motor dan motor custom Indonesia.";
?>

<!DOCTYPE html>
<html style="height:100%">
  <head>
    <meta charset="utf-8">
    <title><?php echo ucwords($title) . " - " . $WEB_NAME?></title>
    <meta name="description" content="<?php echo $WEB_DESC ?>"/>
    <style>
        .cat-title span {
            display: inline-block;
            margin-right: 10px;
        }
        .brand-info {
            opacity: 0.7;
        }
        ul.tipeList {
            list-style: none;
            position: absolute;
            z-index: 999;
            background: white;
            /* padding: 10px; */
            /* border: 1px solid; */
            box-shadow: 0px 3px 10px rgba(0,0,0,0.8);
            max-height: 400px;
            overflow: scroll;
            display:none;
            min-width: 200px;
        }
        .tipeList a {
            color: black;
        }
        .tipeList li {
            border-bottom: 1px solid #d8d8d8;
            padding: 3px 10px;
            cursor: pointer;
        }
        .tipeDown {
            cursor:pointer;
        }
        .tipeDown:hover {
            opacity: .7;
        }
        .tipeList li:hover {
            background: #D3D3D3;
        }
    </style>
  </head>
  <body>

    <?php

    require_once('parts/top/top-header-single-black.php');
    ?>

    <div class="full-wrapper" id="main">
      <div class="container">
        <?php if(($brandExist == 1 && $type == "brand") || ($typeExist == 1 && $type == "tipe")) { ?>
        <div class="cat-title">
          <span>
          <?php
          if($type == "tipe") {
          ?>    
          <a href="http://indomotorart.com/motor/<?php echo str_replace(" ", "-", $brandUrl) ?>" class="brand-parent"><?php echo ucwords($brandUrl) ?></a>
          <?php } ?>
          <h2 style="font-size:50px;"> <?php echo ucwords($title); if($type == "brand") { ?> <i class="tipeDown fa fa-caret-down"></i><?php } ?></h2></span>
          <ul class="tipeList">
              <?php 
              foreach($tipeList as $t) {
                  $tipe = str_replace(" ", "-", $t["name"]);
                  $title2 = str_replace(" ", "-", $title);
                  echo "<a href='http://indomotorart.com/motor/$title2/$tipe'><li>$t[name]</li></a>";
              }
              ?>
          </ul>
          <span class="brand-info">
              <span class="brand-info"><i class="fa fa-paper-plane"></i> Total Post: <?php echo $totalPost ?></span>
              <span class="brand-info"><i class="fa fa-calendar"></i> Last Update: <?php echo $lastDate ?></span>
          </span>
        </div>
        <div class="post-list" id="brand">
          Loading...
        </div>
        <div id="msg-endpost">
        </div>
        <?php
        } else {
          echo "<h2>" . ucwords($type) . " tidak ditemukan.</h2>";
        }
        ?>
      </div>
    </div>

    <?php
    require_once('asset.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/parts/bottom.php');
    ?>

  </body>
  <script type="text/javascript" src="http://indomotorart.com/function/post-feed.js"></script>
  <script type="text/javascript">

  $(document).ready(function() {
    // below is specialized only for new with autoload system
    var newStart = 0;
    var newLimit = 6;
    var newStarted = 0;
    var newMsg = "Maaf! Tidak ditemukan foto yang lebih lama.";
    var is_user_logged_in = <?php echo $is_user_logged_in ?>;
    var printed = 0;
    var reachesPageLimit = 0;

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

    $("#brand").html("");
    showPost("<?php echo $title ?>", newLimit, "brand", "Maaf! Tidak ditemukan foto dengan <?php echo $type . " " . $title ?>. Jadilah yang pertama memposting!", newStart, null, "<?php echo $postType ?>");
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
            showPost("<?php echo $title ?>", newLimit, "brand", newMsg, newStart, "msg-endpost", "<?php echo $postType ?>");
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

    <?php } else { ?>
      $("#new").html("");
      showPost("<?php echo $title ?>", newLimit, "brand", "Maaf! Tidak ditemukan foto dengan <?php echo $type . " " . ucwords($title) ?>. Jadilah yang pertama memposting!", newStart, null, "<?php echo $postType ?>");
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
              showPost("<?php echo $title ?>", newLimit, "brand", "Maaf, tidak ditemukan foto dengan <?php echo $type . " " . ucwords($title) ?> yang lebih lama.", newStart, "msg-endpost", "<?php echo $postType ?>");
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
    <?php } ?>
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
  $(".tipeDown").unbind("click").bind("click", function() {
      if($(".tipeList").css("display") == "none") {
          $(".tipeList").show();
      } else {
          $(".tipeList").hide();
      }
  })
  </script>
</html>
