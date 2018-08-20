<?php

  require_once($_SERVER['DOCUMENT_ROOT'] . "/function/database.php");
  require_once($_SERVER['DOCUMENT_ROOT'] . "/function/account.php");
  require_once($_SERVER['DOCUMENT_ROOT'] . "/function/session.php");
    
  if(isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    // validating username
    if(preg_match("/\n/", $username) == 1) {
      echo 'userWhiteSpace';
      die;
    }
    if(!empty($username) && !empty($password) && !empty($email)) {
      //checking availability
      $checkUser = $conn->query("SELECT ID FROM user WHERE username='$username'");
      $checkEmail = $conn->query("SELECT ID FROM user WHERE email='$email'");
      $return = "already";
      $error = 0;
      if($checkUser->num_rows > 0) {
        $return .= 'User';
        $error++;
      }
      // if ($checkEmail->num_rows > 0) {
      //   $return .= 'Email';
      //   $error++;
      // }
      if($error == 0) {
        $need_verify = $conn->query("SELECT value FROM web_config WHERE name='need_verify'")->fetch_assoc()["value"];
        if($need_verify == 1) {
            $token = $username.uniqid();
        } else {
            $token = "activated";
        }
        $ip = $_SERVER['REMOTE_ADDR'];
        $url = preg_replace("/[^a-zA-Z0-9 ]/", "", $username);
        $url = rtrim($url);
        $url = str_replace(" ", "-", $url);
        $url = strtolower($url);
        $newUser = $conn->query("INSERT INTO user (username, password, email, token, url, ip_address) VALUES('$username', '$password', '$email', '$token', '$url', '$ip')");
        if($newUser) {
          if($need_verify == 1) {
              $headers .= "MIME-Version: 1.0\r\n";
              $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
              mail($email, "Verifikasi E-mail Anda - IndoMotorStyle.com", "Verifikasi e-mail Anda, cukup klik: <a href='http://indomotorart.com/akun/verifikasi?token=$token'>http://indomotorart.com/akun/verifikasi?token=$token</a>", $headers);
          } 
          login($username, $password);
          echo 'success';
          die;
        } else {
          echo 'error';
          die;
        }
      } else {
        echo $return;
      }

    }

  }

?>
