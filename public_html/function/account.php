<?php

  require_once($_SERVER['DOCUMENT_ROOT'] . '/function/database.php');
  require_once($_SERVER['DOCUMENT_ROOT'] . '/function/session.php');

  function is_user_logged_in() {
    if(!isset($_SESSION['user'])) {
      return false;
    }
    if(isset($_SESSION['user']) && !empty($_SESSION['user'])) {
      return true;
    }
  }
    
  function handle_token() {
      if(get_user_meta('token') != "activated") {
      header("Location: http://indomotorart.com/akun/verifikasi");
      exit;  
  }
  
  }
  function login($username, $password) {
    global $conn;
    if(is_user_logged_in()) {
      return "loggedin";
  }

    if(!is_user_logged_in()) {
      $username = mysqli_real_escape_string($conn, $username);
      $password = mysqli_real_escape_string($conn, $password);
      $tryIn = $conn->query("SELECT ID FROM user WHERE username='$username' and password='$password'");
      if($tryIn->num_rows > 0) {
        $_SESSION['user'] = $tryIn->fetch_assoc()['ID'];
        return 'true';
      } else {
        return 'notfound';
      }
    }
  }

  function set_user_meta($meta, $value, $userID = null) {
    global $conn;
    if($userID == null) {
      if(is_user_logged_in()) {
        $userID = $_SESSION['user'];
      } else {
        return false;
      }
    }
    $newData = $conn->query("UPDATE user SET $meta='$value' WHERE ID='$userID'");
  }

  function get_user_meta($meta, $id = null) {
    global $conn;

    if($id == null) {
      if(is_user_logged_in()) {
        $userid = $_SESSION['user'];
      } else {
        return false;
      }
    } else {
      $userid = $id;
    }

    $fetch = $conn->query("SELECT $meta FROM user WHERE ID='$userid'");

    if(!$fetch) {
      return false;
    }

    $fetch = $fetch->fetch_assoc()[$meta];

    return $fetch;

  }

  function get_post_meta($meta,  $postID) {
    global $conn;

    $return = $conn->query("SELECT $meta FROM post WHERE ID='$postID'");

    if(!$return) {
      return false;
    }

    $return = $return->fetch_assoc()[$meta];
    return $return;
  }

  function get_array_user_meta($array, $userID = null) {
    global $conn;
    // the $array is actually not array, just fields that separated by commas
    if($userID == null) {
      $userID = $_SESSION['user'];
    }

    $return = $conn->query("SELECT $array FROM user WHERE ID='$userID'");

    if(!$return) {
      return false;
    }
    // fetching
    $return = $return->fetch_array(MYSQLI_ASSOC);
    return $return;
  }

  function get_um_from_post($meta, $postID) {
    global $conn;

    $userID = get_post_meta("userID", $postID);
    $return = $conn->query("SELECT $meta FROM user WHERE ID='$userID'");

    if(!$return) {
      return false;
    }

    $return = fetch_assoc()[$meta];
    return $return;
  }

  function register($username, $password, $email) {
    global $conn;

    if(!empty($username) && !empty($password) && !empty($email)) {
      $username = mysql_real_escape_string($username);
      $password = mysql_real_escape_string($password);
      $email    = mysql_real_escape_string($email);

      //checking availability
      $checkUser = $conn->query("SELECT ID FROM user WHERE username='$username'");
      $checkEmail = $conn->query("SELECT ID FROM user WHERE email='$email'");
      $return = "already";
      $error = 0;

      if($checkUser->num_rows > 0) {
        $return .= 'User';
        $error++;
      }
      if ($checkEmail->num_rows > 0) {
        $return .= 'Email';
        $error++;
      }
      if($error == 0) {
        $newUser = $conn->query("INSERT INTO user (username, password, email) VALUES(username='$username', password='$password', email='$email')");
        if($newUser) {
          return true;
          die;
          exit;
        } else {
          return false;
          die;
        }

      } else {
        return $return;
      }

      return true;

    }

  }

  function get_user_nick($userID = null) {
    global $conn;

    if($userID == null) {
      $userID = $_SESSION['user'];
    } else {
      $userID = $userID;
    }

    $fetch = $conn->query("SELECT nickname, username FROM user WHERE ID='$userID'");
    $fetch = $fetch->fetch_array();

    $displayName;
    if($fetch['nickname'] === NULL) {
      $displayName = $fetch['username'];
    }  else {
      $displayName = $fetch['nickname'];
    }

    return $displayName;
  }

  function get_user_avatar($userID = null, $res) {

    if($userID == null) {
      $userID = $_SESSION['user'];
    }

    $avatarUrl = get_user_meta("avatar", $userID);
    if($avatarUrl == null) {
      $avatarUrl = "https://freeiconshop.com/wp-content/uploads/edd/person-outline.png";
    }
    $return = "<img src='" . $avatarUrl . "' width='" . $res . "'></img>";
    return $return;
  }

  function get_user_title($userID = null) {
    global $conn;
    if($userID == null) {
      $userID = $_SESSION['user'];
    }
    $title = $conn->query("SELECT title FROM user WHERE ID='$userID'");
    if($title->num_rows <= 0) {
      return false;
    }
    $title = $title->fetch_assoc()['title'];
    switch($title) {
      case 100:
        $title = "admin";
        break;
      case 101:
        $title = "moderator";
        break;
      case 1:
        $title = "Pendatang Baru";
        break;
      case 2:
        $title = "Rider";
        break;
        case 3:
        $title = "Rider Sejati";
        break;
      default:
        $title = "Pendatang Baru";
    }
    return $title;
  }

  function get_user_full_nick($userID = null) {
    global $conn;
    global $HOME_URL;

    if($userID == null) {
      $userID = $_SESSION['user'];
    }

    // getting data
    $userData = get_array_user_meta("url, username, title, rep", $userID);

    $nick = get_user_nick($userID);
    $username = $userData['username'];
    $title = get_user_title($userID);
    $rep = $userData['rep'];
    $url = $userData['url'];
    $fullnick = "$nick <small>(<a href='$HOME_URL/showprofile.php?user=$url'>@$username</a>) ($title) ($rep rep)</small>";
    return $fullnick;
  }

  function get_um_custom($meta, $custom, $customValue) {
    global $conn;

    if(empty($meta) || empty($custom) || empty($customValue)) {
      return false;
    }

    $fetch = $conn->query("SELECT $meta FROM user WHERE $custom='$customValue'");

    if(!$fetch) {
      return false;
    }

    $fetch = $fetch->fetch_assoc()[$meta];

    return $fetch;

  }
?>
