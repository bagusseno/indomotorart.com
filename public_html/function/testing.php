<?php
session_start();
require_once('database.php');
require_once('account.php');
require_once('strings.php');
require_once('../asset.php');

// mail("bagusseno354@gmail.com", "HOY", "ASD");
// $post = $conn->query("SELECT lovedby, love FROM post WHERE ID='1'");
// $x = $post->fetch_assoc()['lovedby'];
// $x = unserialize($x);
// print_r($x);
// $userid = $_SESSION['user'];
// array_slice($x, 28);
// if($x === false) {
//   $x = array();
// }
// $search = array_search($userid, $x);
//
// if($search === FALSE) {
//   echo 'GA KETEMU';
// } else {
//   echo "KETEMU";
// }
//
// var_dump($search);
// var_dump($post);
//
// $f = array(12,321, 312,3123);
// foreach($f as $key => $value) {
//   if($f[$key] == 12) {
//     unset($f[$key]);
//   }
// }
// print_r($f);
//
// echo '<br><br>';
//
// $g = array(123,32,423,1);
// array_push($g, 123);
// print_r($g);
//
// echo 'unserialize<br>';
// $h = "";
// $jk = unserialize($h);
// var_dump($jk);
//
// var_dump(get_user_meta("nickname", 29));
//
// echo '<br>';
//
// $ty = "HAha haha haha ";
// echo preg_replace("/[^A-Za-z0-9 ]/", "", $ty);
// $ty = rtrim($ty);
// echo str_replace(" ", "-", $ty);
// echo $ty . "asdasd";

// echo "<br>";
//
// $userPostID = get_um_from_post("userID", 15);
// var_dump($userPostID);

// echo $_GET['rul'];

// $string = "asdsad";
// $match = preg_match("/\n/", $string);
// if($match == 1) {
//   echo "There is whitespace";
// } else {
//   echo "There is no whitespace";
// }
echo uniqid() . time();

  ?>
  <textarea id="test" rows="10" cols="40"></textarea>
  <Script Language="JavaScript">
  var oImg=document.createElement("IMG");
  oImg.src="http://tw.i4.yimg.com/i/tw/hp/spirit/yahoo_logo.gif";
  test.appendChild(oImg);
  
  function am(asd, dsa) {
      return "HAHA";
  }
  
  var a = am("a", "d");
  console.log(a);
  </Script>
  
