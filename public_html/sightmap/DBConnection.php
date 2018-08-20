<?php

$conn = new mysqli("localhost", "bagusseno_bagusseno", "dyudyu99", "bagusseno_sightmap");

if(!$conn) {
  echo $conn->connect_error;
}

?>
