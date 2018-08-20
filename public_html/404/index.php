<?php
// including important files
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/session.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/database.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/account.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/asset.php');

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Halaman Tidak Ditemukan - <?php echo $WEB_NAME ?></title>
  </head>
  <body>

    <?php

    require_once($_SERVER['DOCUMENT_ROOT'] . '/parts/top/top-header-2.php');

    ?>
    <div class="full-wrapper" id="main">
      <div class="container">
        <h2>Maaf, halaman tidak ditemukan.</h2>
        <a href="<?php echo $HOME_URL ?>">Kembali ke Beranda</a>
      </div>
    </div>
    <?php
    require_once($_SERVER['DOCUMENT_ROOT'] . '/parts/bottom.php');
    ?>
  </body>
</html>
