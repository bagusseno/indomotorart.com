<?php

$conn = new mysqli("localhost", "bagusseno", "dyudyu99", "bagusseno_ims");

$g = $conn->query("SELECT ID, imgsrc FROM post WHERE ID='104'")->fetch_array(MYSQLI_ASSOC);

    $img = $g["imgsrc"];
    $ID = $g["ID"];
    // start create thumb
    list($width, $height, $type) = getimagesize($img);

    // $twidth = 350;
    // $theight = $twidth * $height / $width;
    // if($theight < 232) {
    //     $twidth = 232 * $width / $height;
    //     $theight = 232;
    // }
    // $dst = imagecreatetruecolor($twidth, $theight);
    // switch($type) {
    // case 2:
    //     $source = imagecreatefromjpeg($img);
    //     $imagecreate = "imagejpeg";
    //     break;
    // case 3:
    //     $source = imagecreatefrompng($img);
    //     $imagecreate = "imagepng";
    //     break;
    // }
    // imagecopyresampled($dst, $source, 0, 0, 0, 0, $twidth, $theight, $width, $height);
    // $basename = basename($g["imgsrc"]);
    // $newUrl = "http://indomotorart.com/asset/thumb/thumb-$basename";
    // header("Content-Type: image/png");
    // $jpeg = imagejpeg($dst);
    
    // create thumb
      $t_moveUrl = "../asset/thumb/thumb_" . $uniqid . "_" . str_replace(" ", "-", $img['name']);
      $t_url = "http://indomotorart.com/asset/thumb/thumb_" . $uniqid . "_" . str_replace(" ", "-", $img['name']);
      $t_min_height = 232;
      $t_width = 350;
      $t_height = 350 * $height / $width;
      $dst = imagecreatetruecolor($t_width, $t_height);
      // determine image type
      switch($type) {
          case 2:
              $source = imagecreatefromjpeg($img);
              $imagecreate = "imagejpeg";
              break;
          case 3:
              $source = imagecreatefrompng($img);
              $imagecreate = "imagepng";
              break;
      }
      imagecopyresampled($dst, $source, 0, 0, 0, 0, $t_width, $t_height, $width, $height);
      header("Content-Type: image/jpeg");
      $thumb = imagejpeg($dst);
    // $insertThumb = $conn->query("UPDATE post SET thumb='$newUrl' WHERE ID='$ID'");
    

?>