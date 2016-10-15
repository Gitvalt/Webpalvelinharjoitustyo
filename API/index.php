<?php
require_once("../connect.php");


$array = array(2,3,4,5);

//200 = ok, 400 = no
/*
$uri = explode("API/",$_SERVER["REQUEST_URI"]);
echo json_encode($uri);
*/

if(empty($_GET["type"])){
    Response(400,"Response fail, no data input", null);
} else {
    
    $parameter;
    
    switch($_GET["type"]){
        case "event":
            
            $parameter = GetEventData($_GET["index"]);
            
            if($parameter == false){
                Response(400, "Event does not exist", null);
            } else {
                Response(200, "Response ok", $parameter);    
            }    
            break;
            
        case "events":
            
            $parameter = GetTableData("event");
            
            if($parameter == null){
                Response(400, "No events defined", null);
            } else {
                Response(200, "Response ok", $parameter);
            }
            break;
            
         case "user":
            
            $parameter = GetUserData($_GET["index"]);
            
            if($parameter == false){
                Response(400, "User does not exist", null);
            } else {
                Response(200, "Response ok", $parameter);    
            }    
            break;
            
        case "users":
            
            $parameter = GetTableData("person");
            
            if($parameter == null){
                Response(400, "No persons defined", null);
            } else {
                Response(200, "Response ok", $parameter);
            }
            break;
    }
}


function Response($status, $status_message, $data){
    header('Content-Type: application/json');
    header("HTTP/1.1 $status $status_message");
    
    $response["status"] = $status;
    $response["status_message"] = $status_message;
    
    $response["data"] = $data; 
    
    
    
    echo json_encode($response);
}



?>