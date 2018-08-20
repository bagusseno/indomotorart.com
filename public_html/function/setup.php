<?php

require_once('account.php');
require_once('session.php');
require_once('database.php');
if(isset($_POST) == true) {

  $userID = $_SESSION['user'];
  $nickname = mysqli_real_escape_string($conn, $_POST['nickname']);
  $brand = mysqli_real_escape_string($conn, $_POST['brand']);
  $tipe = mysqli_real_escape_string($conn, $_POST['tipe']);
  $kota = mysqli_real_escape_string($conn, $_POST['kota']);
  $daerah = mysqli_real_escape_string($conn, $_POST['daerah']);
  $avatarUrl = "";

  // if there is avatar upload, store it in a variable
  if(isset($_FILES['avatar'])) {

    $avatar = $_FILES['avatar'];
    $avatarTmp = $avatar['tmp_name'];
    if(file_exists($avatarTmp)) {
      $imgsize = getimagesize($avatarTmp);
      if(!$imgsize || $imgsize === FALSE) {
        echo "salahtipe";
      } else {
        // getting image details
        $imgType = $imgsize['mime']; // image type
        $src_width = $imgsize[0];
        $src_height = $imgsize[1];
        // setting up the function
        switch($imgType) {
          case 'image/jpeg':
            $image = "imagejpeg";
            $imagecreatefrom = "imagecreatefromjpeg";
            break;
          case 'image/png':
            $image = "imagejpeg";
            $imagecreatefrom = "imagecreatefrompng";
            break;
        }
        // creating image
        $dst_img = imagecreatetruecolor(100, 100);
        $src_img = $imagecreatefrom($avatarTmp);
        // determining size
        $new_width = $src_height;
        $new_height = $src_width;
        // determining point, in other words it determines the center point
        if($new_width > $src_width) { // simply, if width > height, just like that
          $h_point = (($src_height - $new_height) / 2);
          // cropping
          imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, 100, 100, $src_width, $new_height);
        } else {
          $w_point = (($src_width - $new_width) / 2);
          // cropping
          imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, 100, 100, $src_width, $new_height);
        }
        // create final image
        $uniqid = uniqid();
        $avatarUrl = $_SERVER['DOCUMENT_ROOT'] . "/asset/photos/pp/" . $uniqid . $avatar['name'];
        $image($dst_img, $avatarUrl, 80);
        if($dst_img)imagedestroy($dst_img);
        if($src_img)imagedestroy($src_img);
        // checking error, in other words the pp is already moved to the new directory
        if(!file_exists($avatarUrl)) {
          echo 'noimagefound';
          die;
        }
        // setting up new form of pp directory
        $avatarUrl = "http://indomotorart.com/asset/photos/pp/" . $uniqid . $avatar['name'];
        $update = $conn->query("UPDATE user SET avatar='$avatarUrl', nickname='$nickname', brand='$brand', tipe='$tipe', kota='$kota', daerah='$daerah' WHERE ID='$userID'") or die(mysqli_error($conn));
      }
    }
    $query = "";
  } else {
    $update = $conn->query("UPDATE user SET nickname='$nickname', brand='$brand', tipe='$tipe', kota='$kota', daerah='$daerah' WHERE ID='$userID'") or die(mysqli_error($conn));
  }

  if(!$update) {
    echo 'fail';
  } else {
    echo 'success';
  }

} else {
  echo 'tidakadadata';
}

?>
