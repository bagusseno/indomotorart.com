<?php
/*

  File name   : Index of lounge
  Description :

  A place that provides public social communication and network. It lets
  registered users to post some words to the public, which whoever can see
  the posted posts. The posted posts can be commented by any registered users.

  Next plan   : Add like or vote system to each post, both post and its comments

*/

// include important files
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/session.php'); // this file will start session
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/database.php'); // this file give database connection
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/account.php'); // this file contains useful functions

// check the parameter
if(isset($_GET['id'])) {
  $postID = $_GET['id'];
} else {
  header("Location: $HOME_URL");
  exit;
}
// check user status
if(is_user_logged_in()) {
  $is_user_logged_in = 1;

  // if user is logged in, then we can fill variables that will be used
  $userID = $_SESSION['user'];
  $userData = $conn->query("SELECT ID, avatar FROM user WHERE ID='$userID'");
  $userData = $userData->fetch_array(MYSQLI_ASSOC);
  $nickname = get_user_full_nick($userID);
  $avatarUrl = $userData["avatar"];
  $avatar = get_user_avatar($userID, 45, 45);

} else {
  // if user is not logged in, we fill none
  $is_user_logged_in = 0;
  $userID = 0;
  $avatar = "";
  $nickname = "";
}

// check user status (verified/not)
if(get_user_meta('token') != "activated") {
  $is_user_activated = 0;
} else {
  $is_user_activated = 1;
}

if(isset($_GET['notif']) && !empty($_GET['notif'])) {
  $UID = $_GET['notif'];

  $exist = $conn->query("UPDATE usernotif SET readed='1' WHERE UID='$UID'") or die(mysqli_error($conn));
}

// handle post
// we will not call the post by given ID, we will cal the parent instead if EXIST
// first, check the parent value of given post ID
$parent = $conn->query("SELECT parent FROM loungepost WHERE ID='$postID'");
$parent = $parent->fetch_assoc()['parent'];
if($parent != null) {
  // it means the given post ID is a child
  $parentID = $parent;
} else {
  $parentID = $postID;
}
// then get the parent ID
$getParent = $conn->query("SELECT * FROM loungepost WHERE ID='$parentID'") or die(mysqli_error($conn));
$getChilds = $conn->query("SELECT * FROM loungepost WHERE parent='$parentID'");

if($getParent->num_rows > 0) {
  // it means there is data
  $getParent = $getParent->fetch_assoc();
  // set up variables
  $userID = $getParent['userID'];
  $content = nl2br($getParent['content']);
  $date = $getParent['date'];
  $username = get_user_meta("username", $userID);
  if($getChilds->num_rows > 0) {
    $display = "block";
  } else {
    $display = "none";
  }
}

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title><?php echo $WEB_NAME ?> - Indonesia Motorcycle Art & Style</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>
  <body>

    <?php

    // get website assets, including CSS and JS
    require_once($_SERVER['DOCUMENT_ROOT'] . '/asset.php');

    // get the TOP part, which contains headers and intro
    require_once($_SERVER['DOCUMENT_ROOT'] . '/parts/top/top-header-2.php');
    ?>

    <div class="full-wrapper" id="main">

      <div class="container">

        <div class="cat-title">
          <h1>Lounge</h1>
        </div>
        Menampilkan postingan oleh <?php echo get_user_full_nick($userID); ?>

        <?php // this is the place for adding list of lounge ?>
        <div class="postlounge-list">
          <?php
            // set up HTML elements
            ?>
            <div class="lounge-item lounge-top" id="post-<?php echo $parentID ?>">
              <div class="userinfo">
                <div class="useravatar">
                  <?php echo get_user_avatar($userID, 32); ?>
                </div>
                <div class="userdetails">
                  <?php echo addslashes(get_user_full_nick($userID)); ?>
                  <br>
                  <small><?php echo $date ?></small>
                </div>
              </div>
              <div class="lounge-content"><?php echo $content ?></div>
              <div class="lounge-actions">
                <small>
                  <a class="comment-btn" data-username="<?php echo $username ?>" data-comment-id="<?php echo $parentID ?>">Comment</a>
                  . <a href="http://indomotorart.com/lounge/showpost.php?id=<?php echo $postID ?>">Share link</a>
                </small>
              </div>
              <small>
                <a class="fa fa-angle-up sc" style="display:<?php echo $display ?>" id="show-<?php echo $parentID ?>" data-comment-id="<?php echo $parentID ?>">
                  Show comments...
                </a>
              </small>
              <div class="lounge-comments" id="lounge-comments-<?php echo $parentID ?>">
                <?php
                // if childs are exist
                if($getChilds->num_rows > 0) {
                  $getChilds = $getChilds->fetch_all(MYSQLI_ASSOC);
                  foreach($getChilds as $child) {
                    $childID = $child['ID'];
                    $childUserID = $child['userID'];
                    $childContent = $child['content'];
                    $childUsername = get_user_meta("username", $childUserID);
                    $childDate = $child['date'];
                    echo "<div class='lounge-item lounge-comment lounge-comment-$parentID' id='post-$childID'>
                      <div class='userinfo'>
                        <div class='useravatar'>" . get_user_avatar($childUserID, 32) . "
                        </div>
                        <div class='userdetails'>
                          " . addslashes(get_user_full_nick($childUserID)) . "
                          <br>
                          <small>$childDate</small>
                        </div>
                      </div>
                      <div class='lounge-content'>$childContent</div>
                      <div class='lounge-actions  '>
                        <small>
                          <a class='comment-btn' data-username='$childUsername' data-comment-id='$parentID'>Reply</a>
                          . <a href='http://indomotorart.com/lounge/showpost.php?id=$childID#post-$childID'>Share link</a>
                        </small>
                      </div>
                      <small>
                      </small>
                    </div>";
                  }

                ?>
              </div>
            </div>
            <?php
          }
             ?>
        </div>
        <div id="msg-endpost">
        </div>

      </div>
    </div>

  </body>
  <style type="text/css">
  .userinfo {
    display: flex;
    align-items: center;
  }
  .useravatar {
    float: left;
  }
  .userdetails {
    display: inline-block;
    margin-left: 15px;
    font-weight: 100!important;
  }
  .userdetails h3 {
    font-weight: 100;
  }
  .loungeform {
    display: inline-block;
    width: 100%;
    margin-top: 20px;
  }
  .cat-title {
  }
  .box {
    width: 100%;
    height: 100px;
    margin-top: 13px;
    font-size: 16px;
    font-family: arial;
    padding: 5px;
    box-sizing: border-box;
  }
  #postbtn {
    float: right;
    margin-top: 10px;
  }
  .postlounge-list {
    margin-top: 20px;
    margin-bottom: 40px;
  }
  .lounge-content {
    margin: 15px 0;
  }
  .lounge-item {
    margin-top: 20px;
    border-bottom: 1px solid #ccc;
    background: #f7f7f7;
  }
  .lounge-comment {
    padding: 15px 0!important;
    margin-right: 5%;
  }
  .lounge-top {
    padding: 15px;
  }
  .sc, .comment-btn {
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -khtml-user-select: none;
    -ms-user-select: none;
    -webkit-touch-select: none;
  }
  .sc:before {
    margin-right: 4px;
  }
  .lounge-comments {
    border-left: 3px solid rgb(204, 204, 204);
    margin-left: 16px;
    padding-left: 3%;
  }
  </style>
  <script type="text/javascript" src="http://indomotorart.com/function/lounge-feed.js"></script>
  <script type="text/javascript">

  $(document).ready(function() {
    // this comment_id is to handle data network of comment system
    // when user clicks "comment" on any post, then the comment id of the post
    // will be stored to this variable. And the variable can be used by other
    // functions or outter functions
    var comment_id;
    // to handle click function on comment button to add a new box below it
    $(document).on("click", ".comment-btn", function() {
      // set a line of name of post based on its id.
      var post = "#post-" + $(this).attr("data-comment-id");
      // see? the comment id is now being stored
      comment_id = $(this).attr("data-comment-id");
      // get user nick
      var user_nick = $(this).attr("data-username");
      console.log(comment_id);
      console.log(post);
      var loggedin = <?php echo $is_user_logged_in ?>;
      if(loggedin == 1){
        var commentBox = '<div style="padding:0 5%;box-sizing: border-box; margin-bottom: 15px;" class="loungeform commentBox">'
          + '<form id="loungeCommentForm">'
            + '<div class="userinfo">'
              + '<div class="useravatar">'
                + "<?php echo $avatar ?>"
              + '</div>'
              + '<div class="userdetails">'
                + '<h3><?php echo addslashes($nickname) ?></h3>'
              + '</div>'
            + '</div>'
            + '<div class="textbox" id="textbox">'
              + '<textarea name="cbox" class="box" id="cbox">@' + user_nick + ' </textarea>'
            + '</div>'
            + '<input type="submit" class="btn" id="postbtn" value="Post"/>'
          + '</form>'
        + '</div>';
        // remove all existing commentBox because it's not used anymore
        $(".commentBox").remove();
        // append the recent posted post
        $(post).append(commentBox);
        $("#cbox").focus();
        $("#cbox").putCursorAtEnd();
      } else {
        // if user is not logged in, redirect to login page
        window.location.replace("http://indomotorart.com/login");
      }
    })
    // to handle submit function of comment, not a single post
    $(document).on("submit", "#loungeCommentForm", function(e) {
      e.preventDefault(); // prevent default used to prevent the default behavior of form submit
      console.log("Commented");
      $.ajax({
        type: "POST",
        url: "http://indomotorart.com/function/lounge-post.php",
        data: {content : $("#cbox").val(), parent : comment_id},
        success: function(res) {
          res = JSON.parse(res);
          console.log("SK" + res);
          if(res == "fail") {
            console.log("FAIL");
            return;
          }

          var newLounge = '<div style="display:none" class="lounge-item lounge-comment lounge-comment-' + comment_id + '" id="post-' + res['ID'] +'">'
            + '<div class="userinfo">'
              + '<div class="useravatar">'
                + res['avatar']
              + '</div>'
              + '<div class="userdetails">'
                + res['usernick'] + '<br><small>pada ' + res['date'] + '</small>'
              + '</div>'
            + '</div>'
            + '<div class="lounge-content">'
              + res['content']
            + '</div>'
            + '<div class="lounge-actions">'
              + '<small><a class="comment-btn" data-comment-id="' + res['ID'] +'">Reply</a> . <a href="http://indomotorart.com/lounge/showpost.php?id="' + res['ID'] + '">Share link</a>  </small>'
            + '</div>'
          + '</div>';

          // below is to handle post post
          // append the result from lounge-post.php to HTML
          $("#show-" + comment_id).fadeIn("fast");
          $("#lounge-comments-" + comment_id).append(newLounge);
          // if the list of lounge comments is invisible, set it to visible
          $("#lounge-comments-" + comment_id).fadeIn('fast');
          // since it's set to invisible, show it, for giving animate effect
          $("#post-" + res['ID']).fadeIn(2000);
          // remove all existing comment box or form
          $(".loungeform").remove();
          // scroll window to the recent published comment
          $('html, body').animate({
            scrollTop: ($("#post-" + res['ID']).offset().top - 200)
          }, 500);
        }
      })
    })
    // to handle showing function of list of comments
    $(document).on("click", ".sc", function() {
      var lounge = "lounge-comments-" + $(this).attr("data-comment-id");
      console.log(lounge);
      // simple, just for showing or closing
      if(document.getElementById(lounge).style.display != "block") {
        document.getElementById(lounge).style.display = "block";
        $(this).addClass("fa-angle-up");
        $(this).removeClass("fa-angle-down");
        $(this).html("Close comments...")
        return;
        console.log(lounge);
      } else if(document.getElementById(lounge).style.display != "none") {
        document.getElementById(lounge).style.display = "none";
        $(this).html("Show comments...")
        $(this).addClass("fa-angle-down");
        $(this).removeClass("fa-angle-up");
        return;
        console.log(lounge);
      }
    })
    // to handle single post submit

  })

  </script>
</html>
