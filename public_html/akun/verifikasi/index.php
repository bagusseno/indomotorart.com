<!DOCTYPE html>
<html style="height:100%">
  <head>
    <meta charset="utf-8">
    <title>Verifikasi Alamat E-mail - IndoMotorStyle</title>
    <meta name="description" content="<?php echo $WEB_DESC ?>"/>
    <?php
    require_once($_SERVER['DOCUMENT_ROOT'] . '/asset.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/function/account.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/function/database.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/function/session.php');

    if(!is_user_logged_in()) {
      header("Location: http://indomotorart.com");
      exit;
    }
    if(get_user_meta('token') == "activated") {
      header("Location: http://indomotorart.com");
      exit;
    }

    if(isset($_GET['token']) && !empty($_GET['token'])) {

      $userId = $_SESSION['user'];
      $token =  $_GET['token'];
      $checkToken = $conn->query("SELECT token FROM user WHERE ID='$userId'");
      $checkToken = $checkToken->fetch_assoc()['token'];

      if($checkToken == $token) {
        $update = $conn->query("UPDATE user SET token='activated' WHERE ID='$userId'");

        if($update){
          header("Location: http://indomotorart.com/akun/pengaturan?status=new");
          exit;
        } else {
          echo "<h1>Terjadi kesalahan. Harap refresh.</h1>";
        }
      } else {
        echo "<h1>Token yang Anda berikan tidak ditemukan.</h1>
        <br><p>Solusi:</p>
        <br>
        <ul><li>Mungkin saja akun Anda telah diaktivasi, sehingga token ini sudah tidak berlaku lagi.</li>
        <li>Cek kembali token yang Anda masukkan.</li>
        <li>Masih mendapati masalah? Hubungi kami.</li>
        </ul>";
      }

    }
    ?>
  </head>
  <body>

    <?php

    require_once($_SERVER['DOCUMENT_ROOT'] . '/parts/top/top-header-2.php');

    ?>

    <div class="page">
      <div class="container">
        <?php

        if(!isset($_GET['token']) && empty($_GET['token'])) {
        ?>
        <h1>Satu langkah lagi...</h1>
        <p>
          Kami telah mengirimkan e-mail verifikasi ke alamat <?php echo get_user_meta('email') ?>. Silakan verifikasi alamat e-mail Anda terlebih dahulu sebelum melanjutkan. Caranya cukup dengan mengklik atau membuka link yang telah kami kirimkan melalui e-mail. Terima kasih, salam satu aspal!
        </p>
        <br>
        <p>
          <a href="javascript:void(0)" id="resend-a">Tidak mendapatkan e-mail dari kami?</a><br><br>
          <div class="resend-section section">
            Solusi:
            <ul>
              <li>Cek spam pada e-mail Anda. E-mail dari kami mungkin saja terdeteksi sebagai spam.</li>
              <li>Perhatikan alamat e-mail Anda. Apakah Anda tidak salah mengetik e-mail?</li>
              <li>Kirim ulang.</li>
            </ul>
            <br>
            <form id="resend-form">
              <input type="hidden" name="resend"/>
              <input type="submit" class="btn resend-email" value="Kirim ulang"/>
              <p id="resend-msg"></p>
            </form>
            <br>
          </div>
        </p>
        <p>
          <a href="javascript:void(0)" id="spam-a">Mengapa perlu verifikasi alamat e-mail?</a><br><br>
          <div class="spam-section section">
            Kami mewajibkan Anda meng-verifikasi alamat e-mail Anda bertujuan untuk mencegah spam. Jika metode ini tidak diberlakukan, bisa saja seseorang mendaftar akun sebanyak-banyaknya dengan bebas.
          </div>
        <?php } ?>
      </div>
    </div>
    <?php
    require_once($_SERVER['DOCUMENT_ROOT'] . '/parts/bottom.php');

    ?>
  </body>
</html>

<style>
.spam-section, .resend-section {
  display: none;
}
</style>
<script type="text/javascript">
$(document).ready(function() {

  var sending = 0;
  $("#resend-form").unbind("submit").bind("submit", function(e) {
    e.preventDefault();
    $("#resend-msg").html("Mengirim...");
    if(sending == 0) {
      sending = 1;
      $(".resend-email").css("opacity", "0.5");
      $.ajax({
        type: "POST",
        url: "http://indomotorart.com/function/resend-verification.php",
        data: {resend : ""},
        success: function(r) {
          console.log("sent" + r);
          if(r == "true") {
            $("#resend-msg").html("Terkirim");
            $(".resend-email").css("opacity", "1");
            sending = 0;
          } else {
            $("#resend-msg").html("Terjadi kesalahan");
            $(".resend-email").css("opacity", "1");
            sending = 0;
          }
        }
      });
    }
  });

  var resendopen = 0;
  var spamopen = 0;
  $("#resend-a").unbind("click").bind("click", function() {
    if(resendopen == 0) {
      $(".section").css("display", "none");
      $(".resend-section").css("display", "block");
      resendopen = 1;
      spamopen = 0;
    } else {
      $(".section").css("display", "none");
      resendopen = 0;
      spamopen = 0;
    }
    console.log(resendopen + " " + spamopen);
  });

  $("#spam-a").unbind("click").bind("click", function() {
    if(spamopen == 0) {
      $(".section").css("display", "none");
      $(".spam-section").css("display", "block");
      spamopen = 1;
      resendopen = 0;
    } else {
      $(".section").css("display", "none");
      spamopen = 0;
      resendopen = 0;
    }
    console.log(resendopen + " " + spamopen);
  })

})
</script>
