<?php

  require_once($_SERVER['DOCUMENT_ROOT'] . '/function/session.php');

  session_destroy();
  header("Location: http://indomotorart.com");
  exit;
?>
