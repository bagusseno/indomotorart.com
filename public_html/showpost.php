<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/session.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/database.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/account.php');
// if no parameter given better to redirect
if(!isset($_GET['url'])) {
  header("Location: $HOME_URL");
}
// if user is not logged in he/she can't reply comments
$is_user_logged_in = 1;
if(!is_user_logged_in()) {
  $is_user_logged_in = 0;
}
$url = $_GET['url'];
$post = $conn->query("SELECT * FROM post WHERE url='$url'");
// if $post == false or in other words it means there is no row like the given utl then show error message
$notfound = 0;
if($post->num_rows <= 0) {
  $notfound = 1;
}
// print_r($post);
$post = $post->fetch_array();
$postID = $post['ID'];
$author = $post['userID'];
$title = $post['title'];
$brand = ucfirst($post['brand']);
$tipe = $post['tipe'];
$modspec = $post['modspec'];
$content = $post['content'];
$imgsrc = $post['imgsrc'];
$love = $post['love'];
$lovedby = $post['lovedby'];
$date = $post['date'];
$url = (string)$post['url'];
// get user info
$user = $conn->query("SELECT username FROM user WHERE ID='$author'");
$userLink = $user->fetch_assoc()["username"];
$userLink = "http://indomotorart.com/showprofile.php?user=$userLink";
if($content != null || !empty($content)) {
  $WEB_DESC = strip_tags($content);
}
// if result is null, replace with a message
if($modspec == NULL) {
  $modspec = "Maaf, OP tidak memberikan keterangan modifikasi.";
}
if($content == NULL) {
  $content = "Maaf, OP tidak memberikan catatan apapun.";
}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title><?php echo $title ?> - IndoMotorStyle</title>
    <meta name="description" content="<?php echo $WEB_DESC ?>"/>
  </head>
  <body>
    <script>var nolimit = 1</script>
    <?php

    // website system
    require_once($_SERVER['DOCUMENT_ROOT'] . '/asset.php');

    // website structures
    require_once($_SERVER['DOCUMENT_ROOT'] . '/parts/top/top-header-2.php');
    ?>
    <div class="full-wrapper" id="main">

      <div class="container">


        <?php
        // checking whether user is logged in or not for jQurey
        if(is_user_logged_in()) {
          $is_user_logged_in = 1;
        } else {
          $is_user_logged_in = 0;
        }
        // countering if post id not found
        if($notfound == 1) {
          echo
            '<h2>Maaf, postingan tidak ditemukan.</h2>
            <a href="' . $HOME_URL . '">Kembali ke Beranda</a>
            ';
        } else {

         ?>
        <div class="post-single" id="single-<?php echo $postID ?>">
          <div class="post-left">
            <div class="single-thumb">
              <img src="<?php echo $imgsrc ?>" class="single-img"/>
            </div>
          </div>
          <div class="post-right">
            <div class="single-info">
              <h2>Informasi Motor</h2>
              <ul>
                <li id="brand"><b><h2>Brand</h2></b><p><?php echo $brand ?></p></li>
                <li id="tipe"><b><h2>Tipe</h2></b><p><?php echo $tipe ?></p></li>
                <li class="single-mod">
                  <h2>Modifikasi</h2>
                  <p><?php echo $modspec ?></p>
                </li>
              </ul>
            </div>
          </div>
          <div class="post-bottom">
            <div class="cat-title">
                <h2><?php echo $title ?></h2>
            </div>
            <div class="single-content">
              <div class="note">
                <div class="author">
                  <a href="<?php echo $userLink ?>"><span class="author-avatar"><?php echo get_user_avatar($author, 32) ?></span>
                  <span class="author-name"><h3><?php echo get_user_nick($author) ?><h3></span></a>
                </div>
                <div class="content">
                  <p><?php echo $content ?></p>
                </div>
              </div>
              <div class="single-love">
                <?php
                  // checking whether user votes or not
                  // variable for placing the class loved
                  $loved = "";
                  if(is_user_logged_in()) {
                    $userID = $_SESSION['user'];

                    $lovedby = unserialize($lovedby);
                    if($lovedby !== false) {
                      $loved = array_search($userID, $lovedby);
                      if($loved === false) {
                        $loved = "fa-heart-o";
                      } else {
                        $loved = "fa-heart loved";
                      }
                    } else {
                      $loved = "fa-heart-o";
                    }

                  } else {
                    $loved = "fa-heart-o";
                  }
                 ?>
                <span data-loveid='<?php echo $postID ?>' class="fa <?php echo $loved ?> love-post" style="font-size: 30px"><?php echo $love ?></span>
              </div>
            </div>
            <div id="comments">
              <h2>Komentar</h2>
              <div class="comment-list">

                <?php
                // getting comments
                // function to compare between parents and childs
                function compareComments($parent, $child) {
                  foreach($child as $c){
                    // do
                    if($c['parent'] === $parent['ID']) {
                      echo
                      "<dl class='comment-item' style='background:#e2e2e2;margin-left: 5%' id='comment-" . $c['ID'] . "'>"
                      . "<dt class='comment-user'>"
                      . "<div class='comment-avatar'>" . get_user_avatar($c["userID"], 32) . "</div>"
                      . "<div class='comment-name'>" . get_user_full_nick($c["userID"]) . " berkomentar..</div></dt>"
                      . "<dt class='comment-content'>" . nl2br($c["content"]) . "</dt>"
                      . "<dt class='comment-date'>pada " . $c["date"] . " <a data-comment-id='" . $c["ID"] . "' href='javascript:void(0)' class='comment-reply'>Reply</a></dt>";

                      if($c['is_parent'] == 1) {
                        compareComments2($c, $child);
                      } else {
                        echo "</dl>";
                      }
                    }
                  }
                  echo "</dl>";
                }

                function compareComments2($parent, $child) {
                  foreach($child as $c){
                    // do
                    if($c['parent'] == $parent['ID']) {
                      echo
                      "<dl class='comment-item' style='background:#efefef;margin-left: 5%' id='comment-" . $c['ID'] . "'>"
                      . "<dt class='comment-user'>"
                      . "<div class='comment-avatar'>" . get_user_avatar($c["userID"], 32) . "</div>"
                      . "<div class='comment-name'>" . get_user_full_nick($c["userID"]) . " berkomentar..</div></dt>"
                      . "<dt class='comment-content'>" . nl2br($c["content"]) . "</dt>"
                      . "<dt class='comment-date'>pada " . $c["date"] . " <a data-comment-id='" . $c["ID"] . "' href='javascript:void(0)' class='comment-reply'>Reply</a></dt>";

                      if($c['is_parent'] == 1) {
                        compareComments($c, $child);
                      } else {
                        echo "</dl>";
                      }
                    }
                  }
                  echo "</dl>";
                }
                // got select NULL value from https://stackoverflow.com/a/3536676 by Marc B (https://stackoverflow.com/users/118068/marc-b) and edited by Kirk (https://stackoverflow.com/users/3082414/kirk)
                $getHighestComment = $conn->query("SELECT * FROM comments WHERE postID='$postID' AND parent IS NULL");
                // highest and parent are different.
                $getParentComment = $conn->query("SELECT * FROM comments WHERE postID='$postID' AND is_parent='1' AND parent IS NOT NULL");
                if($getHighestComment->num_rows <= 0) {
                  echo '<p>Tidak ada komentar ditemukan. Jadilah pertamax!</p>';
                } else {
                  // if there is parent comment, then there are possibilities where child comment is available
                  $getChildComment = $conn->query("SELECT * FROM comments WHERE postID='$postID' and parent IS NOT NULL");
                  // checking the possibilities
                  if($getChildComment->num_rows > 0) {
                    $getChildComment = $getChildComment->fetch_all(MYSQLI_ASSOC);
                  }
                  // get data result as array from parent
                  $getHighestComment = $getHighestComment->fetch_all(MYSQLI_ASSOC);
                  // display each line
                  foreach($getHighestComment as $highest) {
                    echo
                      "<dl class='comment-item' style='background:#efefef' id='comment-" . $highest['ID'] . "'>"
                      . "<dt class='comment-user'>"
                      . "<div class='comment-avatar'>" . get_user_avatar($highest["userID"], 32) . "</div>"
                      . "<div class='comment-name'>" . get_user_full_nick($highest["userID"]) . " berkomentar..</div></dt>"
                      . "<dt class='comment-content'>" . nl2br($highest["content"]) . "</dt>"
                      . "<dt class='comment-date'>pada " . $highest["date"] . " <a data-comment-id='" . $highest["ID"] . "' href='javascript:void(0)' class='comment-reply'>Reply</a></dt>";

                      if($highest['is_parent'] == 1) {
                        // means it has a child or more
                        compareComments($highest, $getChildComment);
                      } else {
                      }
                      echo "</dl>";
                  }
                }

                ?>
              </div>
              <div class="comment-box">
                <?php
                  if(!is_user_logged_in()) {
                    echo "<p><a href='" . $HOME_URL . "/login'>Login</a> untuk memberi komentar.</p>";
                  } else {
                 ?>
                <form id="comment-form" class="comment-form" action="http://indomotorart.com/function/comment.php">
                  <textarea name="comment" id="comment" style="font-family: arial;width:100%; min-height:100px"></textarea>
                  <input type="hidden" name="postID" id="postID" value="<?php echo $postID ?>"/>
                  <br><br>
                  <input class="btn" style="float:right" type="submit" value="Komentar"/>
                </form>
                <?php } ?>
              </div>
            </div>
          </div>
        </div>
      <?php } ?>
      </div>

    </div>

    <?php
    require_once($_SERVER['DOCUMENT_ROOT'] . '/parts/bottom.php');

    ?>
  </body>
  <style>
  #mid-header {
    z-index: 999;
    height: 19px;
    padding: 3px;
    border-bottom: 1px solid;
    position: fixed;
  }
  .mid-nav {
    margin-top: 0;
  }
  .post-left {
    width: calc(100% - 300px);
    float: left;
    display: inline-block;
  }
  .post-right {
    float: right;
    width: 300px;
    padding-left: 20px;
    box-sizing: border-box;
  }
  @media screen and (max-width: 800px) {
      .post-right {
          float: unset;
          display: inline;
      }
      .post-left, .post-bottom {
          width: 100%!important;
      }
  }
  .post-bottom {
    width: calc(100% - 300px);
    display: inline-block;
    margin-top: 15px;
    border-top: 1px solid #ccc;
    padding: 15px 0;
  }
  .single-thumb {
    position: relative;
    display: inline-flex;
    width: 100%;
  }
  .single-img {
    max-width: 100%;
    margin: 0 auto;
    width: 100%;
    height: 100%;
  }
  .single-info li {
    float: left;
    list-style: none;
    padding: 10px;
    background: none!important;
  }
  .single-info ul h2 {
    border-left: 3px solid black;
    padding-left: 8px;
    }
  .single-info ul {
    display: grid;
    grid-gap: 4px;
  }
  .single-content {
    padding: 16px 0;
  }
  .note {
    width: calc(100% - 59.69px);
    display: inline-block;
  }
  .single-love {
    display: inline-block;
    float: right;
  }
  .single-contents {
    display: grid;
    grid-gap: 4px;
  }
  .single-contents .single-love, .single-mod {
    padding: 10px;
  }
  h2 {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 10px;
  }
  .author-avatar {
    float: left;
    margin-right: 10px;
  }
  .author-name h3 {
    font-weight: 100;
  }
  .author-avatar img {
    border-radius: 32px;
  }
  #comments {
    width: 100%;
    box-sizing: border-box;
    margin-top: 34px;
  }
  .content {
    line-height: 1.5em;
    margin-left: 43px;
  }
  .comment-item {
    /* margin-bottom: 12px; */
    margin: 14px 0;
    padding: 15px;
  }
  .comment-box {
    margin-top: 20px
  }
  .comment-form {
    display: inline-block;
    width: 100%;
  }
  .comment-date {
    font-size: 12px;
  }
  .comment-avatar {
    width: 32px;
    float: left;
  }
  .comment-user {
    width: 100%;
    display: inline-block;
  }
  .comment-name {
    display: inline-block;
    margin-top: 10px;
    margin-left: 10px;
  }
  .comment-content {
    margin: 20px 10px;
  }
  .child {
    margin-left: 5%;
  }
  .love-post:before {
      margin-right: 5px;
  }
  </style>
  <script type="text/javascript">
    $(document).ready(function() {

      // below is functions to handle threaded comments
      // below is to handle threaded comment box
      var comment_id;
      $(document).on("click", ".comment-reply", function() {
        var loggedin = <?php echo $is_user_logged_in ?>;
        if(loggedin == 0) {
          window.location.replace("http://indomotorart.com/login");
        }
        comment_id = $(this).attr("data-comment-id");
        var comment_box = '<div class="comment-box comment-reply-box"><form class="comment-form" id="reply-form" action="http://indomotorart.com/function/comment.php">'
                + '<textarea name="comment" id="comment-reply" style="font-family: arial;width:100%; min-height:100px"></textarea>'
                + '<input type="hidden" name="postID" id="postID" value="<?php echo $postID ?>">'
                + '<br><br><input class="btn" style="float:right" type="submit" value="Komentar">'
                + '</form>';
        $(".comment-reply-box").remove();
        $("#comment-" + comment_id).append(comment_box);
      })
      // this is the form submit handler for threaded comments
      $(document).on("submit", "#reply-form", function(e) {
        e.preventDefault();
        console.log("commented");
        // generating variables
        var comment = $("#comment-reply").val();
        var postID = $("#postID").val();
        var data = {comment : comment, postID : postID, parent : comment_id};
        // starting ajax
        $.ajax({
          type: "POST",
          url: "http://indomotorart.com/function/comment.php",
          data: data,
          dataType: "JSON",
          success: function(r) {
            console.log("ajaxed" + r);
            var newComment = "<dl class='comment-item'style='background:#efefef' id='comment-" + r['ID'] + "'>"
            + "<dt class='comment-user'>"
            + "<div class='comment-avatar'> " + r['avatar'] + " </div>"
            + "<div class='comment-name'> " + r['usernick'] + " berkomentar..</div></dt>"
            + "<dt class='comment-content'> " + r['content'] + " </dt>"
            + "<dt class='comment-date'>pada " + r["date"] + " <a data-comment-id='" + r["ID"] + "' href='javascript:void(0)' class='comment-reply'>Reply</a></dt>"
            + "</dl>";
            $(".comment-reply-box").remove();
            $("#comment-" + comment_id).append(newComment);
          }
        });
      });
      // below is a function to send ajax in order to comment.
      // NOTICE: this function is only for the highest level of comment
      $(document).on("submit", "#comment-form", function(e) {
        e.preventDefault();
        console.log("commented");
        // generating variables
        var comment = $("#comment").val();
        var postID = $("#postID").val();
        var data = {comment : comment, postID : postID};
        // starting ajax
        $.ajax({
          type: "POST",
          url: "http://indomotorart.com/function/comment.php",
          data: data,
          dataType: "JSON",
          success: function(r) {
            console.log("ajaxed" + r);
            var newComment = "<dl class='comment-item'style='background:#efefef' id='comment-" + r['ID'] + "'>"
            + "<dt class='comment-user'>"
            + "<div class='comment-avatar'> " + r['avatar'] + " </div>"
            + "<div class='comment-name'> " + r['usernick'] + " berkomentar..</div></dt>"
            + "<dt class='comment-content'> " + r['content'] + " </dt>"
            + "<dt class='comment-date'>pada " + r["date"] + " <a data-comment-id='" + r["ID"] + "' href='javascript:void(0)' class='comment-reply'>Reply</a></dt>"
            + "</dl>";
            $(".comment-list").append(newComment);
          },
          error: function(e) {console.log("ERROR" + e.responseText);}
        })
      })
    })

    // from StackOverFlow by Majid Golshadi https://stackoverflow.com/users/2603921/majid-golshadi the link:https://stackoverflow.com/a/20819663
    $(document).on('click', '.love-post', function() {
      console.log("Clicked");
      var postID = $(this).attr('data-loveid');
      var thisx = $(this);
      var loggedin = <?php echo $is_user_logged_in ?>;
      console.log(postID);
      if(loggedin == 0) {
        window.location.replace("http://indomotorart.com/login");
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
  </script>
</html>
