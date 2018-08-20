<header class="common-header top-header float-header" id="float-header">

  <div class="container">

    <span class="burger">
      <img src="http://indomotorart.com/asset/icon/burger.png" style="cursor: pointer; width: 24px; margin-right: 15px; float: left; display: inline-block; margin-top: 13px; display: none"></img>
    </span>
    <a href="http://indomotorart.com">
      <span class="logo" id="top-logo">
        <img src="http://indomotorart.com/asset/logo.png" id="logo"/>
        <!-- <h1><?php //echo $WEB_NAME ?></h1> -->
      </span>
    </a>
    <span class="nav top-nav menu-nav" id="menu-nav">
      <ul>
        <li id="companyIcon"><a href="" class="fa fa-caret-down"></a></li>
            <ul id="company">
                <li><a href="http://indomotorart.com/showpage.php?id=2">Tentang Kami</a></li>
                <li><a href="http://indomotorart.com/showpage.php?id=3">Kontak Kami</a></li>
                <li><a href="http://indomotorart.com/showpage.php?id=4">FAQ</a></li>
                <li><a href="<?php echo $HOME_URL ?>/showpage.php?id=6">TOS</a></li>
                <li><a href="<?php echo $HOME_URL ?>/showpage.php?id=7">Disclaimer</a></li>
                <li><a href="<?php echo $HOME_URL ?>/showpage.php?id=9">Privacy</a></li>        
            </ul>
        <li><a href="http://indomotorart.com/baru">Foto Baru</a></li>
        <li><a href="http://indomotorart.com/populer">Foto Populer</a></li>
      </ul>
        <div id="search-box" class="mb">
          <form class="search-form" style="position:relative" method="get" action="http://indomotorart.com/search.php">
            <input type="text" id="search-input" class="si-small" name="keyword" placeholder="Cari sesuatu...">
            <button type="submit" id="search-submit" class="ss-small"><a class="fa fa-search"></a></button>
        </form></div>
    </span>

    <span class="nav top-nav user-nav">
      <ul>
        <?php
        if(!is_user_logged_in()) {
        ?>
        <li><a href="http://indomotorart.com/login">Masuk</a></li>
        <li><a href="http://indomotorart.com/register">Daftar</a></li>
        <?php } else { ?>
        <!-- <li style="margin-top:-3px"><a href="javascript:void(0)" class="fa fa-bell top-icon" id="notif-icon"></a></li> -->
        <ul class="dropdown" id="dropdown-notif">
          <?php
          // so, if user is logged in, get notifications from database
          $currentUserID = $_SESSION['user'];
          $notifs = $conn->query("SELECT link, readed, notif, date FROM usernotif WHERE userID='$currentUserID' ORDER BY date DESC LIMIT 5");
          if($notifs != FALSE) {
            if($notifs->num_rows > 0) {
              $notifs = $notifs->fetch_all(MYSQLI_ASSOC);
              $thereIsUnread = 0;
              foreach($notifs as $notif) {
                if($notif['readed'] == 0) {
                  $style = "class='unseen'";
                  $read = "belum dilihat";
                  $thereIsUnread++;
                } else{
                  $style = "";
                  $read = "dilihat";
                }
                if($thereIsUnread != 0) {
                  echo "<style>#notif-icon:after { content: '$thereIsUnread'; position: absolute; color: white; font-size: 14px; background: red; margin-left: -11px; margin-top: -6px; height: 20px; display: inline-flex; width: 20px; font-weight: bold; border-radius: 30px; align-items: center; justify-content: center; }</style>";
                }
                echo "<a href='" . $notif['link'] . "'><li $style>" . $notif['notif'] . " <small>($read)</small></li></a>";
              }

            }
          }
          ?>
        </ul>
        <li style="margin-top:-3px"><a href="javascript:void(0)" class="fa fa-user-circle top-icon" id="account-icon-2"></a></li>
          <ul class="dropdown fa fa-caret-up" id="dropdown-user-2" style="display:none">
            <a href="<?php echo $HOME_URL . '/showprofile.php?user=' . get_user_meta("url")?>"><li>Halo, <?php echo get_user_nick() ?></li></a>
            <?php if(get_user_meta('token') != "activated") { ?>
              <a href="http://indomotorart.com/akun/verifikasi"><li>Verifikasi</li></a>
            <?php } else { ?>
              <a href="http://indomotorart.com/myfeed"><li>My Feed</li></a>
              <a href="http://indomotorart.com/upload"><li>Upload</li></a>
              <a href="http://indomotorart.com/akun/pengaturan"><li>Pengaturan</li></a>
            <?php } ?>
            <a href="http://indomotorart.com/akun/logout.php"><li>Logout</li></a>
          </ul>
        <li><a href="http://indomotorart.com/upload" class="button2 fa fa-plus">Upload</a></li>
      <?php } ?>
      </ul>
    </span>

  </div>
    <div id="float-search-box" class="mb">
        <form class="search-form" style="position:relative" method="get" action="http://indomotorart.com/search.php">
            <input type="text" id="search-input" class="si-small" name="keyword" placeholder="Cari sesuatu...">
            <button type="submit" id="search-submit" class="ss-small"><a class="fa fa-search"></a></button>
        </form>
    </div>
</header>
