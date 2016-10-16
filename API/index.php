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
        case "userCreate":
             if($_SERVER['REQUEST_METHOD'] == "GET"){
                Response(400 ,"not acceptable input", null);   
            }
            
            
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                
                //API/users/:{username}
                
                $id = @$_GET["index"];
                
                if(!empty(@$_POST["password"])){
                        $password = $_POST["password"];
                    } else {
                        Response(400, "password not defined", null);
                }
                
                if(!empty(@$_POST["firstname"])){
                        $firstname = $_POST["firstname"];
                    } else {
                        Response(400, "firstname not defined", null);
                }
                
                if(!empty(@$_POST["lastname"])){
                        $lastname = $_POST["lastname"];
                    } else {
                        Response(400, "lastname not defined", null);
                }
                
                if(!empty(@$_POST["email"])){
                        $email = $_POST["email"];
                    } else {
                        Response(400, "email not defined", null);
                }
                
                if(!empty(@$_POST["phone"])){
                        $phone = $_POST["phone"];
                    } else {
                        Response(400, "phone not defined", null);
                }
                
                if(!empty(@$_POST["address"])){
                        $address = $_POST["address"];
                    } else {
                        Response(400, "address not defined", null);
                }
                
                $respond = InsertUser($id, $password, $firstname, $lastname, $email, $phone, $address);
                
                if($respond == true){
                    Response(200, "Response ok", null);
                } else {
                    Response(400, "Response false", $respond);
                }
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
            
        case "userEventModify":
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                
                $user_data = GetUserData($_GET["owner"]);
                $eventdata = GetUserEvent($_GET["owner"], $_GET["index"]);
                
                $counter = 0;
                
                if($user_data == false){
                    Response(404, "No use found", null);
                } else {
                
                if($eventdata == false){
                    Response(404, "No event found", null);
                } else {
                    
                    if(!empty($_POST["header"])){
                        $header = $_POST["header"];
                        $counter++;
                    } else {
                        $header = $eventdata["header"];
                    }
                    
                    if(!empty($_POST["description"])){
                        $description = $_POST["description"];
                        $counter++;
                    } else {
                        $description = $eventdata["description"];
                    }
                    
                    if(!empty($_POST["startDateTime"])){
                        $startDateTime = $_POST["startDateTime"];
                        $counter++;
                    } else {
                        $startDateTime = $eventdata["startDateTime"];
                    }
                    
                    if(!empty($_POST["endDateTime"])){
                        $endDateTime = $_POST["endDateTime"];
                        $counter++;
                    } else {
                        $endDateTime = $eventdata["endDateTime"];
                    }
                    
                    if(!empty($_POST["location"])){
                        $location = $_POST["location"];
                        $counter++;
                    } else {
                        $location = $eventdata["location"];
                    }
                    
                    if(!empty($_POST["owner"])){
                        $owner = $_POST["owner"];
                        $counter++;
                    } else {
                        $owner = $eventdata["owner"];
                    }
                    
                    //if any changes are detected.
                    if($counter != 0){
                    $result = ModifyUserEvent($_GET["index"], $header, $description, $startDateTime, $endDateTime, $location, $owner);
                    } else {
                        Response(400, "No changes defined", null);
                    }
                    
                    if($result == true){
                        Response(200, "Modified user", GetUserEvent($_GET["owner"], $_GET["index"]));    
                    } else {
                        Response(400, "Modification failure", $result);    
                    }
                }
                }
            } else {
                Response(404, "Invalid get", null);
            }
            break;
            
        case "userModify":
              if($_SERVER['REQUEST_METHOD'] == "POST"){
                
                $user_data = GetUserData($_GET["owner"]);
                    
                if($user_data == false){
                    Response(404, "No use found", null);
                } else {
                

                    if(!empty($_POST["firstname"])){
                        $firstname = $_POST["firstname"];
                    } else {
                        $firstname = $user_data["firstname"];
                    }

                    if(isset($_POST["lastname"])){
                        $lastname = @$_POST["lastname"];
                    } else {
                        $lastname = $user_data["lastname"];
                    }

                    if(isset($_POST["password"])){
                        $password = @$_POST["password"];
                    } else {
                        $password = $user_data["password"];
                    }

                    if(isset($_POST["address"])){
                        $address = @$_POST["address"];
                    } else {
                        $address = $user_data["address"];
                    }

                    if(isset($_POST["phone"])){
                        $phone = @$_POST["phone"];
                    } else {
                        $phone = $user_data["phone"];
                    }

                    if(isset($_POST["email"])){
                        $email = @$_POST["email"];
                    } else {
                        $email = $user_data["email"];
                    }

                    $result = ModifyUser($_GET["index"], $password, $firstname, $lastname, $email, $phone, $address);

                    if($result == true){
                        Response(200, "Modified user", $firstname);    
                    } else {
                        Response(400, "Modification failure", $result);    
                    }
                }
                
            } else {
                Response(404, "Invalid get", null);
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