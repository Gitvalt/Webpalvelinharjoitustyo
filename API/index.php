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
        case "userEvent":
            //API/users/{username}/events/{eventid}
            if($_SERVER['REQUEST_METHOD'] == "GET"){
                $parameter = GetUserEvent($_GET["user"],$_GET["index"]);

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
            
        case "userEvents":
            //API/users/{username}/events/
            if($_SERVER['REQUEST_METHOD'] == "GET"){

                $parameter = GetUserEvents($_GET["index"]);

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
            //API/users/{username}
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
                
                $parameter = GetTableData("user");
                
                if($parameter == null){
                    Response(404, "No persons defined", null);
                } else {
                    Response(200, "Response ok", $parameter);
                }
            }
            
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                Response(404 ,"Not implemented", null);
            }
            
            break;
            
        case "userEventInstert":
            //API/users/username/:{x}
            // --> insert event
            if($_SERVER['REQUEST_METHOD'] == "GET"){
                //Response false
                Response(400, "Invalid GET command", null);
            }
            
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                //create event for user. index = username
                //index = otsikko
                if(isset($_POST["header"]) and isset($_POST["startdatetime"]) and isset($_POST["enddatetime"])) {
                    
                    //all required data exists
                    //validate input
                    if(!empty(@$_GET["index"])){
                        $header = @$_GET["index"];
                    } else {
                        $header = "";
                    }
                    
                    if(!empty(@$_POST["description"])){
                        $desc = @$_POST["description"];
                    } else {
                        $desc = "";
                    }
                    
                    
                    $EventStart = new DateTime($_POST["startdatetime"]);
                    $FormatStart = $EventStart->format("Y-m-d H:i:s");
                    
                    $EventEnd =  new DateTime($_POST["enddatetime"]);
                    $FormatEnd = $EventEnd->format("Y-m-d H:i:s");
                    
                    if(!empty(@$_POST["location"])){
                        $location = @$_POST["location"];
                    } else {
                        $location = "";
                    }
                    
                    $response = InsertEvent($_GET["owner"], $header, $desc, $FormatStart, $FormatEnd, $location);
                    
                    if($response == true){
                        Response(200, "New event created", $response);
                    } else {
                        Response(404, "Creating event failed", $response);    
                    }
                    
                } else {
                Response(404, "Missing required data", null);    
                }
                
                
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