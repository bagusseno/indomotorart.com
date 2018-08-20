<?php

    $conn = new mysqli("localhost", "bagusseno", "dyudyu99", "bagusseno_sosmed");
    
    if(isset($_GET["userloginID"])) {
    
        $username = $_GET["userloginID"];
        $userID = $conn->query("SELECT userID FROM user WHERE username='$username'");
        
        $groupIDs = $conn->query("SELECT groupID FROM membergroup WHERE userID='$userID'");
        
        if($groupIDs->num_rows > 0) {
            
            // fetch all
            $groupIDs = $groupIDs->fetch_all(MYSQLI_ASSOC);
            $allList = [];
            
            foreach($groupIDs as $key=>$value) {
                
                $groupID = $value["groupID"];
                $announce = $conn->query("SELECT content FROM announcements WHERE groupID = '$groupID'");
                $announce = $announce->fetch_all(MYSQLI_ASSOC);

                for($i = 0; $i < sizeof($announce); $i++) {
                    
                    array_push($allList, $announce[$i]["content"]);
                    
                }
                
                
            }
            
            echo json_encode($allList);
            
        } else {
            
            echo "nogroup";
            
        }
        
    } else {
        
        echo 'notset';
        
    }
    
?>