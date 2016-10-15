<?php
require_once("../connect.php");

/*
http://www.restapitutorial.com/lessons/httpmethods.html

*/

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
            //API/events/{x}
            if($_SERVER['REQUEST_METHOD'] == "GET"){
                $parameter = GetEventData($_GET["index"]);

                if($parameter == false){
                    Response(400, "Event does not exist", null);
                } else {
                    Response(200, "Response ok", $parameter);    
                }    
            }
            
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                Response(404 ,"Not implemented", null);
            }
            
            break;
            
        case "events":
            //API/events/
            if($_SERVER['REQUEST_METHOD'] == "GET"){

                $parameter = GetTableData("event");

                if($parameter == null){
                    Response(400, "No events defined", null);
                } else {
                    Response(200, "Response ok", $parameter);
                }
            }
            
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                Response(404 ,"Not implemented", null);
            }
            
            
            break;
            
         case "user":
            //API/users/{x}
            if($_SERVER['REQUEST_METHOD'] == "GET"){
                $parameter = GetUserData($_GET["index"]);

                if($parameter == false){
                    Response(400, "User does not exist", null);
                } else {
                    Response(200, "Response ok", $parameter);    
                }
            }
            
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                Response(404 ,"Not implemented", null);
            }
            
            
            break;
            
        case "users":
            //API/users/
            if($_SERVER['REQUEST_METHOD'] == "GET"){
                
                $parameter = GetTableData("person");
                
                if($parameter == null){
                    Response(400, "No persons defined", null);
                } else {
                    Response(200, "Response ok", $parameter);
                }
            }
            
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                Response(404 ,"Not implemented", null);
            }
            
            break;
            
        default:
            Response(404, "System failure", null);
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