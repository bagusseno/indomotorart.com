<?php

require_once('database.php');
require_once('account.php');
require_once('session.php');

if(isset($_POST['postID'])) {
  // check whether user is logged in or not
  if(!is_user_logged_in()) {
    echo 'unlogged';
    die;
  }
  // get data
  $postID = $_POST['postID'];
  $userID = $_SESSION['user'];
  // the amount of vote that will be added, could be -1 or 1
  $newvote = 0;
  $vote = $conn->query("SELECT lovedby, love, userID FROM post WHERE ID='$postID'");
  $totalLove = $conn->query("SELECT love FROM post WHERE ID='$postID'");

  $totalLove = $totalLove->fetch_assoc()['love'];
  $vote = $vote->fetch_array(MYSQLI_ASSOC);
  $userPostID = $vote['userID'];
  $vote = $vote['lovedby'];

  $vote = unserialize($vote);
  if($vote === false) {
    $vote = array();
  }
  $currentUserVote = array_search($userID, $vote);
  if($currentUserVote === FALSE) {
    $newvote = 1;
    array_push($vote, $userID);
  } else {
    $newvote = -1;
    foreach($vote as $key => $value) {
      if($vote[$key] == $userID) {
        unset($vote[$key]);
      }
    }
    unset($vote[$userID]);
  }
  $totalLove += $newvote;
  $vote = serialize($vote);
  // updating post data
  $update = $conn->query("UPDATE post SET lovedby='$vote', love='$totalLove' WHERE ID='$postID'");
  // updating user data
  $updateUser = $conn->query("UPDATE user SET total_point = total_point + $newvote, rep = rep + $newvote WHERE ID='$userPostID'");
  if($update && $updateUser) {
    $return = array('updown' => $newvote, 'total' => $totalLove);
    echo json_encode($return);
  } else {
    echo '0';
  }
}
?>
