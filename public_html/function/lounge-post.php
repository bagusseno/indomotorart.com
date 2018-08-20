<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/function/database.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/function/account.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/function/session.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/function/strings.php");

if(isset($_POST['content'])) {

  $content = $_POST['content'];
  $userID = $_SESSION['user'];

  // Extract # (Hashtags) keywords if exists
  $tags = extract_tag($content, 0);
  // Extract mention if exist
  $mentions = extract_at("@", $content, 0);
  // replace each of tags with link included
  if($tags != "" || $tags != NULL) {
    foreach($tags as $tag) {
      $noTag = str_replace("#", "", $tag);
      $content = str_replace($tag, '<a href="' . $HOME_URL . '/lounge?filter=tag&value=' . $noTag . '">' . $tag . '</a>', $content);
    }
  }
  // giving link to @ if exists
  if($mentions != "" || $mentions != NULL) {
    foreach($mentions as $mention) {
      $noAt = str_replace("@", "", $mention);
      $content = str_replace($mention, '<a href="' . $HOME_URL . '/showprofile.php?user=' . $noAt . '">' . $mention . '</a>', $content);
    }
  }
  // serialize the $tags so it can be stored on databse
  $tags = serialize($tags);

  if(isset($_POST['parent'])) {
    $parent = $_POST['parent'];
    $insertLounge = $conn->query("INSERT INTO loungepost SET parent='$parent', userID='$userID', content='$content', tags='$tags'") or die(mysqli_error($conn));
  } else {
    $insertLounge = $conn->query("INSERT INTO loungepost SET userID='$userID', content='$content', tags='$tags'") or die(mysqli_error($conn));
  }

  if($insertLounge) {
    $lastID = $conn->insert_id;
    // give notification to the mentioned user
    if($mentions != "" || $mentions != NULL) {
      foreach($mentions as $mention) {
        $userMentionedID = get_um_custom("ID", "username", $noAt);
        $notifMsg = get_user_nick($userID) . " baru saja menyebut agan di salah satu postingannya.";
        $postID = $lastID;
        $UID = uniqid() . time();
        $link = "$HOME_URL/lounge/showpost.php?id=$postID&notif=$UID#post-$postID";
        $newNotif = $conn->query("INSERT INTO usernotif SET userID='$userMentionedID', UID='$UID', byUserID='$userID', notif='$notifMsg', link='$link', readed='0'");
      }
    }
    // JSONing
    $userID = $_SESSION['user'];
    $getPost = $conn->query("SELECT * FROM loungepost WHERE ID='$lastID'");
    $getPost = $getPost->fetch_array(MYSQLI_ASSOC);
    $getPost['avatar'] = get_user_avatar($userID, 32);
    $getPost['usernick'] = get_user_full_nick($userID);
    $getPost['content'] = nl2br($getPost['content']);
    echo json_encode($getPost);
  } else {
    echo 'fail';
  }
}

?>
