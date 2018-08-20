<?php
header("Content-Type: image/jpeg");

$conn = new mysqli("localhost", "bagusseno", "dyudyu99", "bagusseno_ims");

$getAllImg = $conn->query("SELECT imgsrc FROM post")->fetch_all(MYSQLI_ASSOC);

    $img = $g[1]["imgsrc"];
    // start create thumb
    list($width, $height, $type) = getimagesize($img);
    echo "<br>WIDTH:$width <BR> HEIGHT: $height <BR> TYPE: $type";
    
    $twidth = 350;
    $theight = 350 * $height / $width;
    if($theight < 232) {
        $twidth = 232 * $width / $height;
        $theight = 232;
    }
    $dst = imagecreatetruecolor($twidth, $theight);
    switch($type) {
    case IMGTYPE_JPEG:
        $source = imagecreatefromjpeg($img);
        $imagecreate = "imagejpeg";
        break;
    case IMGTYPE_PNG:
        $source = imagecreatefrompng($img);
        $imagecreate = "imagepng";
        break;
    }
    imagecopyresized($dst, $source, 0, 0, 0, 0, $twidth, $theight, $width, $height);
    imagejpeg($dst);
    imagedestroy($dst);

?>