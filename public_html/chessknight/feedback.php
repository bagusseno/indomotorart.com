<?php

$conn = new mysqli("localhost", "bagusseno", "dyudyu99", "bagusseno_chessknight");

if(isset($_GET["feed"])) {
    $city = $_GET["city"];
    $country = $_GET["country"];
    $feed = $_GET["feed"];
    
    $conn->query("INSERT INTO feedback (country, city, feed) VALUES ('$country', '$city', '$feed')");
    
    if($conn) {
        echo 'success';
    } else {
        echo 'fail';
    }
}

?>