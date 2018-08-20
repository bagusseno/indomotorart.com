<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/session.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/database.php');

if(isset($_POST['followType']) && isset($_POST['followID'])) {
    $userID = $_SESSION['user'];
    $fType = $_POST['followType'];
    $fID = $_POST['followID'];
    if(!isset($_SESSION['user'])) {
        echo 'loggedout';
        // OUT
    } else {
        // continue
        // check if user is already followed
        if($fType == "tipe") {
            $check = $conn->query("SELECT ID FROM follow WHERE userID='$userID' AND tipeID='$fID'");
        } elseif($fType == "community") {
            $check = $conn->query("SELECT ID FROM follow WHERE userID='$userID' AND communityID='$fID'");
        }
        if($check->num_rows > 0) {
            echo 'already';
            // OUT
        } else {
            // continue
            if($fType == "tipe") {
                $new = $conn->query("INSERT INTO follow (userID, tipeID) VALUES ('$userID', '$fID')");
            } elseif($fType == "community") {
                $new = $conn->query("INSERT INTO follow (userID, communityID) VALUES ('$userID', '$fID'");
            }
            
            if($new) {
                echo 'success';
            } else {
                echo 'fail';
                echo $fType;
            }
        }
    }
}

?>