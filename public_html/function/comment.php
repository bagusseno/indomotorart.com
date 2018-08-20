<?php
header("Content-Type: application/json");
require_once($_SERVER['DOCUMENT_ROOT'] . "/function/database.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/function/account.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/function/session.php");

if(isset($_POST['comment'])) {

  $comment = mysqli_real_escape_string($conn, $_POST['comment']);
  $postID = mysqli_real_escape_string($conn, $_POST['postID']);
  $userID = $_SESSION['user'];
  // make sure the comment is not empty
  if(empty($comment) || empty($postID)) {
    echo 'empty';
    die;
  }

  // get user ID from post
  $userPostID = get_post_meta("userID", $postID);

  // update user (of post)'s reputation
  $updateUser = $conn->query("UPDATE user SET rep = rep + 2 WHERE ID='$userPostID'");
  $updateUser = $conn->query("UPDATE user SET rep = rep + 2 WHERE ID='$userID'");

  if(!isset($_POST['parent'])) {
    // this means this comment is on the highest level or first level, no parent above it!
    // so we have all we needed, then do inserting
    $insertComment = $conn->query("INSERT INTO comments (postID, userID, content, love) VALUES ('$postID', '$userID', '$comment', 0)");
  } else {
    $parentID = $_POST['parent'];
    $insertComment = $conn->query("INSERT INTO comments (postID, userID, parent, content, love) VALUES ('$postID', '$userID', '$parentID', '$comment', 0)");
  }


  if($insertComment) {
    $lastID = $conn->insert_id;
    $getComment = $conn->query("SELECT * FROM comments WHERE ID='$lastID'");
    $getComment = $getComment->fetch_array();
    $getComment['avatar'] = get_user_avatar($userID, 32);
    $getComment['usernick'] = get_user_full_nick($userID);
    $getComment['content'] = nl2br($getComment['content']);
    echo json_encode($getComment);

    // setting the is_parent field for parent = true
    if(isset($parentID)) {
      $updateIsParent = $conn->query("UPDATE comments SET is_parent='1' WHERE ID='$parentID'");
    }
  } else {
    echo 'error' . $conn->error;
  }

}

?>
