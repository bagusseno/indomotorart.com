<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/session.php');
require_once($_SERVER['DOCUMENT_ROOT'] . "/function/strings.php");
echo 'included';
var_dump($_POST['tipe']);var_dump($_POST['brand']);
if(!empty($_FILES['upload']['name']) && !empty($_POST['title']) && !empty($_POST['brand']) && !empty($_POST['tipe'])) {
    echo 'aa';
  $img = $_FILES['upload'];
  $title = mysqli_real_escape_string($conn, $_POST['title']);
  $brand = mysqli_real_escape_string($conn, $_POST['brand']);
  $tipe = mysqli_real_escape_string($conn, $_POST['tipe']);
  $mod = mysqli_real_escape_string($conn, $_POST['mod']);
  $cap = mysqli_real_escape_string($conn, $_POST['caption']);
  $imgurl;
  // validating
  $file = $img['tmp_name'];
  if(file_exists($file)) {
    $imgsize = getimagesize($file);
    list($realWidth, $realHeight, $imgType) = $imgsize;
    if(!$imgsize || $imgsize === FALSE) {
      $msg_upload = "Anda memasukkan tipe gambar yang salah!";
    } else {
      echo 'c';
      $uniqid = uniqid();
    $imgurl = $_SERVER["DOCUMENT_ROOT"] . "/asset/photos/" . $uniqid . "_" . str_replace(" ", "-", $img['name']);
    echo $imgurl;
    // save the original image
  $newImg = move_uploaded_file($file, $imgurl);
      // create thumb
      $t_moveUrl = "../asset/thumb/thumb_" . $uniqid . "_" . str_replace(" ", "-", $img['name']);
      $t_url = "http://indomotorart.com/asset/thumb/thumb_" . $uniqid . "_" . str_replace(" ", "-", $img['name']);
      $t_min_height = 232;
      $t_width = 350;
      $t_height = 350 * $realHeight / $realWidth;
      $dst = imagecreatetruecolor($t_width, $t_height);
      echo 'd';
      // determine image type
      switch($imgType) {
          case 2:
              $source = imagecreatefromjpeg($imgurl);
              $imagecreate = "imagejpeg";
              break;
          case 3:
              $source = imagecreatefrompng($imgurl);
              $imagecreate = "imagepng";
              break;
      }
      imagecopyresampled($dst, $source, 0, 0, 0, 0, $t_width, $t_height, $realWidth, $realHeight);
      $thumb = $imagecreate($dst, $t_moveUrl, 100);
      var_dump($thumb);
      var_dump($newImg);
      echo 'f';
      //   resize image if the width is too large
        if($realWidth > 1200) {
            echo 'resize';
            $twidth = 1009;
            $theight = $twidth * $realHeight / $realWidth;
            if($theight < 668) {
                $twidth = 668 * $realWidth / $realHeight;
                $theight = 668;
            }
            $dst = imagecreatetruecolor($twidth, $theight);
            var_dump($dst);
            echo $imgType;
            switch($imgType) {
            case 2:
                $source = imagecreatefromjpeg($imgurl);
                $imagecreate = "imagejpeg";
                break;
            case 3:
                $source = imagecreatefrompng($imgurl);
                $imagecreate = "imagepng";
                break;
            }
            echo $imagecreate;
            var_dump($source);
            $tes = imagecopyresampled($dst, $source, 0, 0, 0, 0, $twidth, $theight, $realWidth, $realHeight);
            var_dump($tes);
            echo 'pemisah';
            $newImg = $imagecreate($dst, $imgurl, 100);
            var_dump($newImg);
        } 
        $imgurl = "http://indomotorart.com/asset/photos/" . $uniqid . "_" . str_replace(" ", "-", $img['name']);
      $userID = $_SESSION["user"];
      if(!$newImg || !$thumb) {
        $msg_upload = "Terjadi error";
        echo "AdaERror";
      // insert new log
      $newLog = $conn->query("INSERT INTO log (loc, msg, userID) VALUES('upload gambar', 'Gagal', '$userID')");
      }
    }
  }

  if($newImg && $thumb) {
      echo 'a';
    $newLog = $conn->query("INSERT INTO log (loc, msg, userID) VALUES('upload gambar', 'Berhasil', '$userID')");
    $userID = $_SESSION['user'];
    $nick = get_user_meta('nickname');
    if($nick == "" || $nick == null || empty($nick)) {
      $nick = get_user_meta('username');
    }
    // create new safe url
    $url = preg_replace("/[^a-zA-Z0-9 -]/", "", $title . "-" . hexdec(uniqid()));
    $url = rtrim($url);
    $url = str_replace(" ", "-", $url);
    // manage tags
    $tags = extract_tag($cap, 0);
    $noTags = array();
    // replace each of tags with link included
    if($tags != "" || $tags != NULL) {
        // initialize the final variable
        $complete = array();
        // turns the content into an array
        $arrayCap = explode(" ", $cap);
        // check each arrayed content
        foreach($arrayCap as $c) {
            // check each tag, if matches then replace it with href
            foreach($tags as $tag) {
                $noTag = str_replace("#", "", $tag);
                $x = preg_replace('/[^a-zA-Z0-9-_\.#]/','', $c);
                array_push($noTags, $noTag);
                if($x == $tag) {
                    $c = str_replace($tag, '<a href="' . $HOME_URL . '/search.php?tag=' . $noTag . '">' . $tag . '</a>', $c);
                    echo "replaced";
                }
            }
            // push the $c to final variable
            array_push($complete, $c);
            
        }
        // turns array into string
        $complete = implode(" ", $complete);
    }
    $noTags = serialize($noTags);

    $inputData = $conn->query("INSERT INTO post SET userID='$userID', url='$url', title='$title', brand='$brand', tipe='$tipe', modspec='$mod', content='$complete', tags='$noTags', imgsrc='$imgurl', thumb='$t_url'");
    $updateUser = $conn->query("UPDATE user SET rep = rep + 5 WHERE ID='$userID'");
    // checking the title
    $totalUserPost = $conn->query("SELECT * FROM post WHERE userID='$userID'");
    $totalUserPost = $totalUserPost->num_rows;
    echo mysqli_error($conn);
    echo $totalUserPost;
    if($totalUserPost >= 24) {
      set_user_meta("title", "3");
      echo 'TITLE 3';
    }
    elseif($totalUserPost >= 14) {
      set_user_meta("title", "2");
        echo 'TITLE 2';
    }
    elseif($totalUserPost >= 4) {
      set_user_meta("title", "1");
      echo 'TITLE 3';
    }
    if(!$inputData && !$updateUser) {
      echo '<script>alert("Maaf! Terjadi kesalahan. Harap refresh dan ulangi.")</script>';
    } else {
        header("Location: http://indomotorart.com/showpost.php?url=$url");
        exit;
    }
  }
  echo "YEYH GA ADA ERROR";
}

?>