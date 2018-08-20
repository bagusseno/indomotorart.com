<?php

  require_once($_SERVER['DOCUMENT_ROOT'] . "/function/database.php");
  require_once($_SERVER['DOCUMENT_ROOT'] . "/function/account.php");
  require_once($_SERVER['DOCUMENT_ROOT'] . "/function/session.php");

  if(isset($_POST['lounge'])) {

    // handle filter
    $specific = null;
    if(isset($_POST['filter'])) {
      $filter = $_POST['filter'];
      $value = $_POST['filterValue'];
    } else {
      $value = "";
    }

    $start = $_POST['start'];
    $limit = $_POST['limit'];
    $msg = $_POST['msg'];
    $limitQuery = " LIMIT $start, $limit";
    $getLounge = $conn->query("SELECT * FROM loungepost WHERE tags LIKE '%$value%' and parent IS NULL ORDER BY date DESC $limitQuery") or die(mysqli_error($conn));
    $getChilds = $conn->query("SELECT * FROM loungepost WHERE parent IS NOT NULL ORDER BY date");

    if($getLounge->num_rows > 0) {
      // it means there is data
      $getLounge = $getLounge->fetch_all(MYSQLI_ASSOC);
      if($getChilds->num_rows > 0) {
        $getChilds = $getChilds->fetch_all(MYSQLI_ASSOC);
      }
      // declare new array
      $finalLoungeJson = array();
      // putting additional variables
      foreach($getLounge as $lounge) {
        $userID = $lounge['userID'];
        $lounge['avatar'] = get_user_avatar($userID, 32);
        $lounge['usernick'] = get_user_full_nick($userID);
        $lounge['username'] = get_user_meta("username", $userID);
        $lounge['content'] = nl2br($lounge['content']);
        $lounge['childs'] = array();
        // detecting if it has a child or more
        foreach($getChilds as $child) {
          if($child['parent'] == $lounge['ID']) {
            $userID = $child['userID'];
            $child['avatar'] = get_user_avatar($userID, 32);
            $child['usernick'] = get_user_full_nick($userID);
            $child['username'] = get_user_meta("username", $userID);
            $child['content'] = nl2br($child['content']);
            array_push($lounge['childs'], $child);
          }
        }
        array_push($finalLoungeJson, $lounge);
      }

      echo json_encode($finalLoungeJson);
    } else {
      echo 'notfound';
    }

  }
