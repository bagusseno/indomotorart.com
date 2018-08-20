<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/function/account.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/session.php');

if(isset($_POST['resend'])) {

  $email = get_user_meta("email");
  $token = get_user_meta("token");
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

  $resend = mail($email, "Verifikasi E-mail Anda - IndoMotorStyle.com", "Verifikasi e-mail Anda, cukup klik: <a href='http://indomotorart.com/akun/verifikasi?token=$token'>http://indomotorart.com/akun/verifikasi?token=$token</a>", $headers);

  if($resend) {
    echo 'true';
  } else {
    echo 'false';
  }
}

?>
