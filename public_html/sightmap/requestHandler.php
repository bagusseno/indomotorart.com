<?php

require_once "DBConnection.php";
require_once "exceptionHandler.php";
require_once "XENOFramework.php";

$authed = false;

// determing request method
$request = $_GET; // default
switch($_SERVER["REQUEST_METHOD"]) {
    case "POST":
        $request = $_POST;
        break;
    case "GET":
        $request = $_GET;
        break;
}

// authenticating access
if(isset($request["username"], $request["password"])) {
    $authed = auth($request["username"], $request["password"]);
}

// if authentication succeed
if($authed) {
    
    // options for process and response
    if(isset($request["query"])) {
        
        // get and escape provided query
        $query = escape($conn, $request["query"]);
        $query = str_replace("\'", "'", $query); // passing single quote 
        
        // request query from the server
        $response = array();
        $response["request"] = $conn->query($query);
        
        // handling exception
        if(!$response["request"]) {
            echo mysqli_error($conn); 
            die(); // stop execution
        }
        
        // set up query result attributes
        $response["status"]["num_rows"] = $response["request"]->num_rows;
        
        // handle fetch system if exists
        if(isset($request["fetchMethod"])) {
            $fetchMethod = escape($conn, $request["fetchMethod"]);
            if($fetchMethod == "assoc") {
                $response["request"] = $response["request"]->fetch_assoc();
            }
            if($fetchMethod == "all") {
                $response["request"] = $response["request"]->fetch_all(MYSQLI_ASSOC);
            }
            if($fetchMethod == "array") {
                $response["request"] = $response["request"]->fetch_array(MYSQLI_ASSOC);
            } 
        } else {
            unset($response["request"]);
        }
        
        echo json_encode($response);
        
    }

} else {
    echo "Access denied. Authentication failed.";
}

?>
