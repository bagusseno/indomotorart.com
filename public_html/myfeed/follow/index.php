<?php
// including important files
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/session.php');
if(!isset($_SESSION['user'])) {
    header("Location: http://indomotorart.com");
    exit;
}
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/database.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/account.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/asset.php');
// handles search
if(isset($_GET["keyword"])) {
    $keyword = mysqli_real_escape_string($conn, $_GET["keyword"]);
    // handle keywords
    $keywords = str_replace(" ", "|", $keyword);
    $pregreplace = preg_replace('/(honda|yamaha|kawasaki|suzuki|bmw|benelli|aprilia|piaggio|vespa|mv|agusta|harley|harley-davidson|ktm|kymco|ducati)/', "", $keyword);
    $keywords = strtolower($keywords);
    if($pregreplace != $keyword) { // found
        if(str_word_count($keyword) > 1) {
            // exception for spaced brand
            if(preg_match('/(piaggio vespa|mv agusta|harley davidson)/', $keyword)) {
                if(str_word_count($keyword) > 2) {
                    $find = "name REGEXP '^($keywords)'";
                } else {
                    $find = "brandID REGEXP '($keywords)'";
                }
            } else {
                $find = "name REGEXP '^($keywords)'";
            }
            // tipe
            echo 'found';
        } else {
            $find = "brandID REGEXP '($keywords)'";
            echo 'notfound';   
        }
    } else {
        $find = "name REGEXP '($keywords)'";
    }
    // get default data (existing motorcycles just like Yamaha>Jupiter MX)
    // the how to use variable on MySQL REGEX was got from https://stackoverflow.com/a/20519526 by Ronald Swets (https://stackoverflow.com/users/2916208/ronald-swets)
    $default = $conn->query("SELECT * FROM tipe WHERE $find");
    echo mysqli_error($conn);
    if($default->num_rows <= 0) {
        $content = "Pencarian tidak ditemukan.";
    } else {
        $default = $default->fetch_all(MYSQLI_ASSOC);
        foreach($default as $d) {
            $brand = ucfirst($d["brandID"]);
            $name = $d["name"];
            $totalPost = $conn->query("SELECT ID FROM post WHERE tipe='$name'");
            $totalPost = $totalPost->num_rows;
            $content .= "<dt><p>$brand $name <span class='search_r_right'>$totalPost post(s) | <a href='javascript:void(0)' class='follow-item' data-type='tipe' data-id='$name'>Follow</a></span></p></dt>";
        }
    }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Follow My Feed - <?php echo $WEB_NAME ?></title>
  </head>
  <body style="height:unset">

    <?php

    require_once($_SERVER['DOCUMENT_ROOT'] . '/parts/top/top-header-2.php');

    ?>
    <div class="full-wrapper" id="main">
      <div class="container-1040">
        <div class="cat-title">
          <a href="http://indomotorart.com/myfeed">Kembali ke My Feed</a> <h1>Follow</h1>
        </div>
        <div class="content">
          <form id="search-form" method="get" action="http://indomotorart.com/myfeed/follow" style="margin-bottom: 15px">
            <input type="text" id="search-input" name="keyword" placeholder="Cari tipe motor (Contoh: Yamaha Jupiter MX)">
            <button type="submit" id="search-submit"><a class="fa fa-search"></a></button>
            </form>
            <?php 
            if(isset($_GET["keyword"])) {
            ?>
            <h2 style="font-weight: 100">Hasil pencarian <?php echo $keyword ?></h2>
            <?php
            }
            ?>
            
        <p class="search-result"><?php echo $content ?></p>
        </div>
      </div>
    </div>
    <?php
    require_once($_SERVER['DOCUMENT_ROOT'] . '/parts/bottom.php');
    ?>
  </body>
  <script type="text/javascript">
      $(".follow-item").click(function() {
          // what to follow? Type (motorcycle) or specified community?
          var followType = $(this).attr("data-type");
          // specified item hierarcy
          var followID = $(this).attr("data-id");
          // store this follow text
          var thisID = $(this);
          $.ajax({
              url: "http://indomotorart.com/function/follow.php",
              type: "POST",
              data: {"followType" : followType, "followID" : followID},
              success: function(r) {
                  console.log("ajaxed");
                  if(r == "success" || r == "already") {
                      thisID.html("Followed");
                  }
                  if(r == "loggedout") {
                      thisID.html("Mohon <a href='http://indomotorart.com/login'>login</a> dahulu.");
                  }
              }
          });
      })
  </script>
</html>
