<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/database.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/session.php');

function get_page_meta($meta, $id) {
  global $conn;
  $return = $conn->query("SELECT $meta FROM page WHERE ID='$id'");
  return $return->fetch_assoc()[$meta];
}

function update_page_meta($meta, $value, $id) {
  global $conn;

  $metas = "";
  $values = "";
  if(is_array($meta)) {

    // set array to a string
    // meta
    foreach($meta as $m) {
      $metas .= $m . ',';
    }
    rtrim($metas, ',');
    // value
    foreach($value as $v) {
      $values .= "'$v'" . ",";
    }
    rtrim($values, ',');

    $updateMeta = $conn->query("REPLACE INTO page ($metas) VALUES ($values) WHERE ID='$id'");

  } else {
    $updateMeta = $conn->query("UPDATE page SET $meta='$value' WHERE ID='$id'");
  }
}

?>
