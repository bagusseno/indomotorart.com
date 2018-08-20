<?php
require_once('function/session.php');
require_once('function/database.php');
require_once('function/account.php');

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
    <title><?php echo $WEB_NAME ?> - Galeri Foto Motor Indonesia</title>
    <meta name="description" content="<?php echo $WEB_DESC ?>"/>
    <meta name="google-site-verification" content="jPkltx-PbGRqsCRF9j44R7V4jwr0sMLGd5JLFJNrk48" />
  </head>
  <body>

    <?php
    
    // website structures
    require_once('parts/top.php');
    ?>

    <div class="full-wrapper" id="main">

      <div class="container">
        <div class="popular-section">
            <div class="cat-title">
              <h2 style="display:inline-block;">Foto Populer</h2> <a href="<?php echo $HOME_URL ?>/populer">more <i class="fa fa-external-link"></i></a>
            </div>
            <div class="post-list" id="trending">
              Loading...
            </div>
        </div>
        <div class="cat-title" id="fotoBaru">
          <h2 style="display:inline-block;">Foto Baru</h2><span> <a href="<?php echo $HOME_URL ?>/baru">more <i class="fa fa-external-link"></i></a></span>
        </div>
        <div class="post-list" id="new">
          Loading...
        </div>
        <div id="msg-endpost">
        </div>
      </div>

    </div>
    
    <?php
    if($_GET["status"] == "new") {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/parts/popup.php";
    }
    require_once('parts/bottom.php');
    // website system
    require_once('asset.php');
    ?>
  </body>

  <script type="text/javascript" src="<?php echo $HOME_URL ?>/function/post-feed.js"></script>
  <script type="text/javascript">
  // load posts
  $(document).ready(function() {

    console.log("ready");

    $("#trending").html("");
    showPost("trend", 4, "trending", "Maaf! Tidak ditemukan foto trending.", 0);

    // below is specialized only for new with autoload system
    <?php
    if(isset($_GET['post'])) {
    ?>
    var newStart = <?php echo $_GET['post']; ?>;
    <?php } else { ?>
    var newStart = 0;
    <?php } ?>
    var newLimit = 8;
    var newStarted = 0;
    var newMsg = "Selamat! Kamu telah mencapai dasar! Tidak ditemukan lagi foto yang lebih lama.";;
    var is_user_logged_in = <?php echo $is_user_logged_in ?>;
    var printed = 0;
    var reachesPageLimit = 0;
    var entirePageLimit = 32;
    var currentAmount = 0;        

    function showMsgPageLimit() {
      console.log("ASDSADaMMM");
      reachesPageLimit = 1;
      if(reachesPageLimit == 1) {
        newStart = document.getElementById("new").childNodes.length;
        console.log("AHSDOHOASD");
        $("#msg-endpost").append("<a href='<?php echo $HOME_URL ?>/baru?post=" + (newStart) + "'>Lihat yang lebih lama <i class='fa fa-arrow-right'></i></a>");
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
    var count = showPost("new", newLimit, "new", "Maaf! Tidak ditemukan foto baru.", newStart);
    console.log(count);
    newStart += newLimit;
    if(newStart > entirePageLimit) {
        newStart = entirePageLimit;
    }
    printed += newLimit;
    $(window).scroll(function() {
        if(newStarted == 0) {
          if($(window).scrollTop() > $("html").height() - window.innerHeight - 350) {
            setTimeout(function() {
              newStarted = 1;
              console.log("triggered");
            }, 300);
          }
        }

        if(newStarted == 1) {
          if(outRes == 1 && printed < entirePageLimit) {
            showPost("new", newLimit, "new", newMsg, newStart, "msg-endpost");
            printed += newLimit;
            console.log("HASUDHI" + outRes);
          }
          if(outRes == 0 || printed >= entirePageLimit) {
            if(reachesPageLimit == 0) {
              showMsgPageLimit();
              console.log("HEHE" + outRes);
            }
          }
          newStart += newLimit;
            if(newStart > entirePageLimit) {
                newStart = entirePageLimit;
            }
          newStarted = 0;
        }

    })

    <?php } else { ?>
      $("#new").html("");
      showPost("new", newLimit, "new", "Maaf! Tidak ditemukan foto baru.", newStart);
      newStart += newLimit;
      if(newStart > entirePageLimit) {
        newStart = entirePageLimit;
      }
      printed += newLimit;
      $(window).scroll(function() {
          if(newStarted == 0) {
            if($(window).scrollTop() > $("html").height() - window.innerHeight - 350) {
              setTimeout(function() {
                newStarted = 1;
                console.log("triggered");
              }, 300);
            }
          }

          if(newStarted == 1) {
            if(outRes != 0 && printed < entirePageLimit) {
              showPost("new", newLimit, "new", newMsg, newStart, "msg-endpost");
              printed += newLimit;
            }
            if(outRes != 0 && printed >= entirePageLimit) {
              if(reachesPageLimit == 0) {
                showMsgPageLimit();
              }
            }
            newStart += newLimit;
            if(newStart > entirePageLimit) {
                newStart = entirePageLimit;
            }
            newStarted = 0;
          }

      })
    <?php } ?>
  });
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
  $("#motor").change(function() {
        $("#filtered").html("");
        $(".tipe-home").css("display", "none");
        console.log(showPost($(this).val(), 4, "filtered", "<div class='maaf'>Maaf! Tidak ditemukan foto motor " + $(this).val() + ". Jadilah yang pertama memposting!</div>", 0, null, "brand"));
        
        if($(this).val() != "") {
            $(".tidakAda").html("");
            $("#pilihTipe").css("display", "none");
            merk = $("#motor").val();
            tipe = $("#" + merk).val();
            $("#input-tipe").val(tipe);
            console.log(merk + tipe);
            if(merk == "honda") {
                $(".tipe").css("display", "none");
                $("#honda").css("display", "unset");
            }
            if(merk == "yamaha") {
                $(".tipe").css("display", "none");
                $("#yamaha").css("display", "unset");
            }
            if(merk == "kawasaki") {
                $(".tipe").css("display", "none");
                $("#kawasaki").css("display", "unset");
            }
            if(merk == "suzuki") {
                $(".tipe").css("display", "none");
                $("#suzuki").css("display", "unset");
            }
            if(merk == "mv\ agusta") {
                $(".tipe").css("display", "none");
                $("select[id='mv agusta']").css("display", "unset");
            }
            if(merk == "piaggio\ vespa") {
                $(".tipe").css("display", "none");
                $("select[id='piaggio vespa']").css("display", "unset");
            }
            if(merk == "aprilia") {
                $(".tipe").css("display", "none");
                $("#aprilia").css("display", "unset");
            }
            if(merk == "bmw") {
                $(".tipe").css("display", "none");
                $("#bmw").css("display", "unset");
            }
            if(merk == "ducati") {
            tipe =
                $(".tipe").css("display", "none");
                $("#ducati").css("display", "unset");
            }
            if(merk == "ktm") {
                $(".tipe").css("display", "none");
                $("#ktm").css("display", "unset");
            }
            if(merk == "benelli") {
                $(".tipe").css("display", "none");
                $("#benelli").css("display", "unset");
            }
            if(merk == "harley-davidson") {
                $(".tipe").css("display", "none");
                $("#harley-davidson").css("display", "unset");
            }
            if(merk == "bajaj") {
            tipe =
                $(".tipe").css("display", "none");
                $("#bajaj").css("display", "unset");
            }
            if(merk == "kymco") {
                $(".tipe").css("display", "none");
                $("#kymco").css("display", "unset");
            }
            if(merk == "") {
                $(".tipe").css("display", "none");
            }
        }    
  });
  $(".tipe-home").change(function() {
      if($(this).val() != "") {
        $("#filtered").html("");
        showPost($(this).val(), 4, "filtered", "<div class='maaf'>Maaf! Tidak ditemukan foto motor " + $(this).val() + ". Jadilah yang pertama memposting!</div>", 0, null, "tipe");  
      }
  })
  </script>
</html>
