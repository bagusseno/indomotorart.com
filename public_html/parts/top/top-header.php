<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/account.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/parts/top/top-header-single.php'); ?>


<header class="burger-header">
  <div class="container">
    <span class="nav menu-nav burger-nav">
      <ul>
        <li><a href="<?php echo $HOME_URL ?>/showpage.php?id=2">Tentang Kami</a></li>
        <li><a href="<?php echo $HOME_URL ?>/showpage.php?id=3">Kontak Kami</a></li>
        <li><a href="<?php echo $HOME_URL ?>/showpage.php?id=4">FAQ</a></li>
        <li><a href="<?php echo $HOME_URL ?>">Beranda</a></li>
        <li><a href="<?php echo $HOME_URL ?>/populer">Foto Populer</a></li>
        <li><a href="<?php echo $HOME_URL ?>/baru">Foto Baru</a></li>
      </ul>
    </span>
</div>
</header>