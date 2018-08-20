<?php
// including important files
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/session.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/database.php');

// $cap = "#tess #te! aja";
// echo $cap . "<br>";
// $tags = extract_tag($cap, 0);
// print_r($tags);
// echo "<br>" . json_encode($tags) . "<br>";
// replace each of tags with link included
// if($tags != "" || $tags != NULL) {

//     $complete = array();

//     $arrayCap = explode(" ", $cap);

//     echo json_encode($arrayCap);
//     foreach($arrayCap as $c) {
    
//         foreach($tags as $tag) {
//             $noTag = str_replace("#", "", $tag);
//             $x = preg_replace('/[^a-zA-Z0-9-_\.#]/','', $c);
//             echo $tag . " " . $c , "<br>";
//             array_push($noTags, $noTag);
//             if($x == $tag) {
//                 $c = str_replace($tag, '<a href="' . $HOME_URL . '/search.php?tag=' . $noTag . '">' . $tag . '</a>', $c);
//                 echo "replaced";
//             }
//         }
        
//         array_push($complete, $c);
        
//     }
//   $complete = implode(" ", $complete);
//   echo $complete;
// }

$string = array("honda cbr", "honda arteza");
var_dump($string); echo "<br>";
$cbr = preg_grep('/cbr/', $string, $match);
$hondacbr = preg_grep('/cbr/', $string, $match);
$honda = preg_grep('/honda/', $string);
var_dump($cbr); echo "<br>";
var_dump($hondacbr); echo "<br>";
var_dump($honda);
?>

<ul>
        <li id="companyIcon"><a href="" class="fa fa-caret-down"></a></li>
        <ul id="company">
        <li><a href="http://indomotorart.com/showpage.php?id=2">Tentang Kami</a></li>
        <li><a href="http://indomotorart.com/showpage.php?id=3">Kontak Kami</a></li>
        <li><a href="http://indomotorart.com/showpage.php?id=4">FAQ</a></li></ul>
        <style>
        select {
            float: left;
        }
            #company {
            position: absolute;
            top: 48px;
            background: black;
            padding: 10px;
            margin-left: -21px;
            display: none;
            }
            
            #companyIcon:hover ~ #company {
                display: block!important;
            }
            
            #company li {
                float: unset;
                margin-bottom: 10px;
            }
        </style>
      </ul>
      <div style="width: 400px">
      <select><option>HAAAAA</option></select>
      <select><option>HAAAA</option></select>
      </div>