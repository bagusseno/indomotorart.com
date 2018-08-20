<?php

    $conn = new mysqli("localhost", "bagusseno", "dyudyu99", "bagusseno_sosmed");
    
    if(isset($_POST["username"]) && isset($_POST["password"])) {
    
        $username = $_POST["username"];
        $password = $_POST["password"];
        
        $query = $conn->query("SELECT * FROM user WHERE username='$username' AND password='$password'");
        
        if($query->num_rows > 0) {
            
            echo "success";
            
        } else {
            
            echo "fail";
            
        }
        
    } else {
        
        echo 'not set';
        
    }

    
?>