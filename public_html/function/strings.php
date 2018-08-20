<?php

function extract_tag($string, $type) {

    preg_match_all('/#(\w+)/', $string, $matches);
    $return = array();

    foreach($matches[$type] as $match) {
      $match = rtrim(trim($match), ",");
      array_push($return, $match);
    }

    if(empty($return)) {
      $return = NULL;
    }
    return $return;

}

function extract_at($char, $string, $type) {
  preg_match_all('/' . $char . '(\w+)/', $string, $matches);
  $return = array();

  foreach($matches[$type] as $match) {
    $match = rtrim(trim($match), ",");
    array_push($return, $match);
  }

  if(empty($return)) {
    $return = NULL;
  }
  return $return;
}
//
// $strings = "I'd like to go to #panama asdas #JNGGGOL";
// $tags = extract_tag($strings, 0);
// if($tags != NULL || $tags != "") {
//   foreach($tags as $tag) {
//     $strings = str_replace($tag, "<font color='blue'>" . $tag . "</font>", $strings);
//   }
//   print_r($tags);
// } else {
//   echo 'NOTFOUDN';
// }
// echo $strings;
?>
