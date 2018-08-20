<?php

  require_once($_SERVER['DOCUMENT_ROOT'] . "/function/database.php");
  require_once($_SERVER['DOCUMENT_ROOT'] . "/function/account.php");
  require_once($_SERVER['DOCUMENT_ROOT'] . "/function/session.php");

  if(isset($_POST['type'])) {
    // allright, im sorry for the confusing type and kind. Type could be new and trend. Where kind
    // used to determine something that has more child inside just like showing post for user
    // or showing post for specified brand

    $kind = $_POST['kind'];
    $note = $_POST['note'];
    $type = $_POST['type'];
    $start = $_POST['start'];
    $limited = $_POST['limit'];
    if($limited == 0) {
      $limited = "";
    } else {
      $limited = " LIMIT $start, $limited";
    }

    if($type == "trend") {
      $postJson = $conn->query("SELECT * FROM post WHERE love > 2 ORDER BY date DESC" . $limited);

      if($postJson->num_rows <= 0) {
        echo 'notfound';
      } else {
        $postJson = mysqli_fetch_all($postJson, MYSQLI_ASSOC);
        $finalPostJson = array();
        if($postJson) {
          foreach($postJson as $line) {
          // get total comments
            $postID = $line['ID'];
            $totalComments = $conn->query("SELECT * FROM comments WHERE postID='$postID'");
            $totalComments = $totalComments->num_rows;
            $line["tComments"] = $totalComments;
            // manage loves
            $userID = $line['userID'];
            $lovedby = unserialize($line['lovedby']);
            if(is_user_logged_in()) {
              $currentUserID = $_SESSION['user'];
              $loved = array_search($currentUserID, $lovedby);
              if($loved === false){
                $line["loved"] = '0';
              } else {
                $line["loved"] = '1';
              }
            } else {
              $line["loved"] = '0';
            }
            $line["nickname"] = get_user_nick($userID);
            if($line["tipe"] != null) {
              $line["motor"] = ucfirst(get_user_meta("brand",$userID)) . " " . get_user_meta("tipe", $userID);
            } else {
              $line["motor"] = "Motor (?)";
            }
            $line["userurl"] = get_user_meta("url", $userID);
            $line["rep"] = get_user_meta("rep", $userID);
            $line["userTitle"] = get_user_title($userID);
            array_push($finalPostJson, $line);
          }
          echo json_encode($finalPostJson);
        } else {
          echo 'error';
        }
      }
    }

    if($type == "new") {
      $postJson = $conn->query("SELECT * FROM post ORDER BY date DESC " . $limited);

      if($postJson->num_rows <= 0) {
        echo 'notfound';
      } else {
        $postJson = mysqli_fetch_all($postJson, MYSQLI_ASSOC);
        $finalPostJson = array();
        if($postJson) {
          foreach($postJson as $line) {
            // get total comments
            $postID = $line['ID'];
            $totalComments = $conn->query("SELECT * FROM comments WHERE postID='$postID'");
            $totalComments = $totalComments->num_rows;
            $line["tComments"] = $totalComments;
            // manage loves
            $userID = $line["userID"];
            $lovedby = unserialize($line['lovedby']);
            if($lovedby === false) {
              $lovedby = array();
            }
            if(is_user_logged_in()) {
              $currentUserID = $_SESSION['user'];
              $loved = array_search($currentUserID, $lovedby);
              if($loved === false){
                $line["loved"] = '0';
              } else {
                $line["loved"] = '1';
              }
            } else {
              $line["loved"] = '0';
            }
            // replace nickaname with Username
            $line['nickname'] = get_user_nick($userID);
            if($line["tipe"] != null) {
              $line["motor"] = ucfirst(get_user_meta("brand",$userID)) . " " . get_user_meta("tipe", $userID);
            } else {
              $line["motor"] = "Motor (?)";
            }
            $line["userurl"] = get_user_meta("url", $userID);
            $line["rep"] = get_user_meta("rep", $userID);
            $line["userTitle"] = get_user_title($userID);
            array_push($finalPostJson, $line);
          }
          echo json_encode($finalPostJson);
        } else {
          echo 'error';
        }
      }
    }

    if($type == "all") {
      if($kind == "search") {
        $postJson = $conn->query("SELECT * FROM post WHERE title LIKE '%$note%' OR content LIKE '%$note%' OR modspec LIKE '%$note%' OR brand LIKE '%$note%' OR tipe LIKE '%$note%' ORDER BY date DESC") or mysqli_error($conn);
      } elseif($kind == "tag") {
        $postJson = $conn->query("SELECT * FROM post WHERE tags LIKE '%$note%'") or mysqli_error($conn);
      } else {
        $postJson = $conn->query("SELECT * FROM post ORDER BY date DESC");
      }
      if($postJson->num_rows <= 0) {
        echo 'notfound';
      } else {
        $postJson = mysqli_fetch_all($postJson, MYSQLI_ASSOC);
        // handle tag
        if($kind == "tag") {
          $foundIt = 0;
        }
        $finalPostJson = array();
        if($postJson) {
          foreach($postJson as $line) {
              // get total comments
            $postID = $line['ID'];
            $totalComments = $conn->query("SELECT * FROM comments WHERE postID='$postID'");
            $totalComments = $totalComments->num_rows;
            $line["tComments"] = $totalComments;
            if($kind == "tag") {
              $tags = unserialize($line['tags']);
              $findTag = in_array($note, $tags);
              if($findTag) {
                $foundIt = 1;
              } else {
                unset($line);
                continue;
              }
            }
            $userID = $line['userID'];
            $lovedby = unserialize($line['lovedby']);
            if($lovedby === false) {
              $lovedby = array();
            }
            if(is_user_logged_in()) {
              $currentUserID = $_SESSION['user'];
              $loved = array_search($currentUserID, $lovedby);
              if($loved === false){
                $line["loved"] = '0';
              } else {
                $line["loved"] = '1';
              }
            } else {
              $line["loved"] = '0';
            }
            $line["nickname"] = get_user_nick($userID);
            if($line["tipe"] != null) {
              $line["motor"] = ucfirst(get_user_meta("brand",$userID)) . " " . get_user_meta("tipe", $userID);
            } else {
              $line["motor"] = "Motor (?)";
            }
            $line["userurl"] = get_user_meta("url", $userID);
            $line["rep"] = get_user_meta("rep", $userID);
            $line["userTitle"] = get_user_title($userID);
            array_push($finalPostJson, $line);
          }
          if($foundIt == 0 && $kind == "tag") {
            echo 'notfound';
            die();
          }
          echo json_encode($finalPostJson);
        } else {
          echo 'error';
        }
      }
    }

    if($type != "trend" && $type != "new") {
      // probably the type is a user ID to get user posts or specified brand
      if($kind == "user") {
        $postJson = $conn->query("SELECT * FROM post WHERE userID='$type' ORDER BY date DESC $limited");
        if($postJson->num_rows <= 0) {
          echo 'notfound';
        } else {
          $postJson = mysqli_fetch_all($postJson, MYSQLI_ASSOC);
          $finalPostJson = array();
          if($postJson) {
            foreach($postJson as $line) {
                // get total comments
            $postID = $line['ID'];
            $totalComments = $conn->query("SELECT * FROM comments WHERE postID='$postID'");
            $totalComments = $totalComments->num_rows;
            $line["tComments"] = $totalComments;
              $userID = $line['userID'];
              $lovedby = unserialize($line['lovedby']);
              if($lovedby === false) {
                $lovedby = array();
              }
              if(is_user_logged_in()) {
                $currentUserID = $_SESSION['user'];
                $loved = array_search($currentUserID, $lovedby);
                if($loved === false){
                  $line["loved"] = '0';
                } else {
                  $line["loved"] = '1';
                }
              } else {
                $line["loved"] = '0';
              }
              $line["nickname"] = get_user_nick($userID);
              if($line["tipe"] != null) {
                $line["motor"] = ucfirst(get_user_meta("brand",$userID)) . " " . get_user_meta("tipe", $userID);
              } else {
                $line["motor"] = "Motor (?)";
              }
              $line["userurl"] = get_user_meta("url", $userID);
              $line["rep"] = get_user_meta("rep", $userID);
              $line["userTitle"] = get_user_title($userID);
              array_push($finalPostJson, $line);
            }
            echo json_encode($finalPostJson);
          } else {
            echo 'error';
          }
        }
      } elseif ($kind == "brand"){
        $postJson = $conn->query("SELECT * FROM post WHERE brand='$type' ORDER BY date DESC $limited");

        if($postJson->num_rows <= 0) {
          echo 'notfound';
        } else {
          $postJson = mysqli_fetch_all($postJson, MYSQLI_ASSOC);
          $finalPostJson = array();
          if($postJson) {
            foreach($postJson as $line) {
                // get total comments
                $postID = $line['ID'];
                $totalComments = $conn->query("SELECT * FROM comments WHERE postID='$postID'");
                $totalComments = $totalComments->num_rows;
                $line["tComments"] = $totalComments;
              $userID = $line['userID'];
              $lovedby = unserialize($line['lovedby']);
              if($lovedby === false) {
                $lovedby = array();
              }
              if(is_user_logged_in()) {
                $currentUserID = $_SESSION['user'];
                $loved = array_search($currentUserID, $lovedby);
                if($loved === false){
                  $line["loved"] = '0';
                } else {
                  $line["loved"] = '1';
                }
              } else {
                $line["loved"] = '0';
              }
              $line["nickname"] = get_user_nick($userID);
              if($line["tipe"] != null) {
                $line["motor"] = ucfirst(get_user_meta("brand",$userID)) . " " . get_user_meta("tipe", $userID);
              } else {
                $line["motor"] = "Motor (?)";
              }
              $line["userurl"] = get_user_meta("url", $userID);
              $line["rep"] = get_user_meta("rep", $userID);
              $line["userTitle"] = get_user_title($userID);
              array_push($finalPostJson, $line);
            }
            echo json_encode($finalPostJson);
          } else {
            echo 'error';
          }
        }
      } elseif ($kind == "tipe") {
            $postJson = $conn->query("SELECT * FROM post WHERE tipe='$type' ORDER BY date DESC $limited");

        if($postJson->num_rows <= 0) {
          echo 'notfound';
        } else {
          $postJson = mysqli_fetch_all($postJson, MYSQLI_ASSOC);
          $finalPostJson = array();
          if($postJson) {
            foreach($postJson as $line) {
                // get total comments
                $postID = $line['ID'];
                $totalComments = $conn->query("SELECT * FROM comments WHERE postID='$postID'");
                $totalComments = $totalComments->num_rows;
                $line["tComments"] = $totalComments;
              $userID = $line['userID'];
              $lovedby = unserialize($line['lovedby']);
              if($lovedby === false) {
                $lovedby = array();
              }
              if(is_user_logged_in()) {
                $currentUserID = $_SESSION['user'];
                $loved = array_search($currentUserID, $lovedby);
                if($loved === false){
                  $line["loved"] = '0';
                } else {
                  $line["loved"] = '1';
                }
              } else {
                $line["loved"] = '0';
              }
              $line["nickname"] = get_user_nick($userID);
              if($line["tipe"] != null) {
                $line["motor"] = ucfirst(get_user_meta("brand",$userID)) . " " . get_user_meta("tipe", $userID);
              } else {
                $line["motor"] = "Motor (?)";
              }
              $line["userurl"] = get_user_meta("url", $userID);
              $line["rep"] = get_user_meta("rep", $userID);
              $line["userTitle"] = get_user_title($userID);
              array_push($finalPostJson, $line);
            }
            echo json_encode($finalPostJson);
          } else {
            echo 'error';
          }
        }
      }
    }

    if($type=="feed") {
        $whereare = "";
        $userID = $_SESSION['user'];
        if(!isset($userID)) {
            echo 'unlogged';
            die();
        }
        // get all followed types and put it on a string with OR
        $getFollow = $conn->query("SELECT tipeID FROM follow WHERE userID='$userID'");
        $getFollow = $getFollow->fetch_all(MYSQLI_ASSOC);
        $more = 0;
        foreach($getFollow as $g) {
            $tipeID = $g["tipeID"];
            if($more == 1) {
                $whereare .= " OR tipe=" . "'$tipeID'";
            } elseif($more == 0) {
                $whereare .= "'$tipeID'";
            }
            $more = 1;
        } 
        $postJson = $conn->query("SELECT * FROM post WHERE tipe=$whereare ORDER BY date DESC");

      if($postJson->num_rows <= 0) {
        echo 'notfound';
      } else {
        $postJson = mysqli_fetch_all($postJson, MYSQLI_ASSOC);
        $finalPostJson = array();
        if($postJson) {
          foreach($postJson as $line) {
            // get total comments
            $postID = $line['ID'];
            $totalComments = $conn->query("SELECT * FROM comments WHERE postID='$postID'");
            $totalComments = $totalComments->num_rows;
            $line["tComments"] = $totalComments;
            // manage loves
            $userID = $line["userID"];
            $lovedby = unserialize($line['lovedby']);
            if($lovedby === false) {
              $lovedby = array();
            }
            if(is_user_logged_in()) {
              $currentUserID = $_SESSION['user'];
              $loved = array_search($currentUserID, $lovedby);
              if($loved === false){
                $line["loved"] = '0';
              } else {
                $line["loved"] = '1';
              }
            } else {
              $line["loved"] = '0';
            }
            // replace nickaname with Username
            $line['nickname'] = get_user_nick($userID);
            if($line["tipe"] != null) {
              $line["motor"] = ucfirst(get_user_meta("brand",$userID)) . " " . get_user_meta("tipe", $userID);
            } else {
              $line["motor"] = "Motor (?)";
            }
            $line["userurl"] = get_user_meta("url", $userID);
            $line["rep"] = get_user_meta("rep", $userID);
            $line["userTitle"] = get_user_title($userID);
            array_push($finalPostJson, $line);
          }
          echo json_encode($finalPostJson);
        } else {
          echo 'error';
        }
      }
    }
    die();
  }

  echo 'error';

?>
