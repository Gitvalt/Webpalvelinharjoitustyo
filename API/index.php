<?php
require_once("../sql-connect.php");

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
            $requestMethod = $_SERVER['REQUEST_METHOD'];
            // When creating event index = header, else = id         
            
            switch($requestMethod){
                case "GET":
                    $parameter = GetUserEvent($_GET["user"], $_GET["index"]);

                    if($parameter == false){
                        Response(400, "Event does not exist", null);
                    } else {
                        Response(200, "Response ok", $parameter);    
                    }    
                    break;
                case "POST":
                    //create event for user. index = username

                    //index = otsikko
                    if(isset($_GET["user"]) and isset($_POST["startdatetime"]) and isset($_POST["enddatetime"])) {

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

                        $response = InsertEvent($_GET["user"], $header, $desc, $FormatStart, $FormatEnd, $location);

                        if($response == true){
                            Response(200, "New event created", $response);
                        } else {
                            Response(404, "Creating event failed", $response);    
                        }

                        } else {
                        Response(404, "Missing required data", null);    
                        }  
                    break;
                    
                case "PUT":
                    $user_data = GetUserData($_GET["user"]);
                    $eventdata = GetUserEvent($_GET["user"], $_GET["index"]);
                
                        $counter = 0;
                
                        if($user_data == false){
                            Response(404, "No use found", null);
                        } else {

                        if($eventdata == false){
                            Response(404, "No event found", null);
                        } else {

                        $input = GetInput();

                        if(!empty($input["header"])){
                            $header = $input["header"];
                            $counter++;
                        } else {
                            $header = $eventdata["header"];
                        }

                        if(!empty($input["description"])){
                            $description = $input["description"];
                            $counter++;
                        } else {
                            $description = $eventdata["description"];
                        }

                        if(!empty($input["startDateTime"])){
                            $startDateTime = $input["startDateTime"];
                            $counter++;
                        } else {
                            $startDateTime = $eventdata["startDateTime"];
                        }

                        if(!empty($input["endDateTime"])){
                            $endDateTime = $input["endDateTime"];
                            $counter++;
                        } else {
                            $endDateTime = $eventdata["endDateTime"];
                        }

                        if(!empty($input["location"])){
                            $location = $input["location"];
                            $counter++;
                        } else {
                            $location = $eventdata["location"];
                        }

                        $owner = $_GET["user"];

                        //if any changes are detected.
                        if($counter != 0){
                        $result = ModifyUserEvent($_GET["index"], $header, $description, $startDateTime, $endDateTime, $location, $owner);
                        } else {
                            Response(400, "No changes defined", null);
                        }

                        if($result == true){
                            Response(200, "Modified user", GetUserEvent($_GET["user"], $_GET["index"]));    
                        } else {
                            Response(400, "Modification failure", $result);    
                        }
                    }
                    }
                     
                    break;
            
                case "DELETE":
                        $result = DeleteEvent($_GET["user"], $_GET["index"]);

                        if($result == true){
                            Response(200,"Deleted event", true);  
                        } else {
                            Response(404,"Not implemented", null);  

                        }
                        break;

                default:
                        Response("404", "Invalid http header", $requestMethod);
                        break;
                    } 
            //end of switch $requestMethod
            break;
             
            
        case "events":
            if($_SERVER['REQUEST_METHOD'] == "GET"){
                $parameter = GetTableData("event");
                
                if($parameter == null){
                    Response(404, "No events defined", null);
                } else {
                    Response(200, "Events found", $parameter);
                }
                
            }
            break;
        
            
        case "userEvents":
            //API/users/{username}/events/
            if($_SERVER['REQUEST_METHOD'] == "GET"){
            
                $parameter = GetUserEvents($_GET["index"]);

                if($parameter == null){
                    Response(404, "No events defined", null);
                } else {
                    Response(200, "Events found", $parameter);
                }
            }
            
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                Response(404 ,"Not implemented", null);
            }
            
            
            break;
            
         case "user":
            
            $requestMethod = $_SERVER['REQUEST_METHOD'];
            // When creating event index = header, else = id         
            
            switch($requestMethod){
                case "GET":
                    $parameter = GetUserData($_GET["index"]);

                    if($parameter == false){
                        Response(400, "User does not exist", null);
                    } else {
                        Response(200, "Response ok", $parameter);    
                    }
                break;
                    
                case "POST":
                        $id = @$_GET["index"];
                 
                        if(!empty(@$_POST["password"])){
                                //! ENCRYPTAUS
                                $password = $_POST["password"];

                        } else {
                                Response(404, "password not defined", null);
                        }

                        if(!empty(@$_POST["firstname"])){
                                $firstname = $_POST["firstname"];
                            } else {
                                $firstname = "";
                        }

                        if(!empty(@$_POST["lastname"])){
                                $lastname = $_POST["lastname"];
                            } else {
                                $lastname = "";
                        }

                        if(!empty(@$_POST["email"])){
                                $email = $_POST["email"];
                            } else {
                                Response(404, "email not defined", null);
                        }

                        if(!empty(@$_POST["phone"])){
                                $phone = $_POST["phone"];
                            } else {
                                $phone = "";                }

                        if(!empty(@$_POST["address"])){
                                $address = $_POST["address"];
                            } else {
                                $address = "";
                        }

                        if(empty($id) or empty($password) or empty($email)){
                            Response(404,"Form data missing;" . $password, null);
                        } else {
                            $respond = InsertUser($id, $password, $firstname, $lastname, $email, $phone, $address);
                            if($respond == true){
                                Response(200, "Response ok", null);
                            } else {
                                Response(400, "Response false", $respond);
                            }
                        }
                break;
                
                case "PUT":
                        $user_data = GetUserData($_GET["index"]);

                        if($user_data == false){
                            Response(404, "No user found", null);
                        } else {

                             $input = GetInput();
                            if(empty($input)){
                                Response(400, "noinput", null);
                            }

                            if(!empty($input["firstname"])){
                                $firstname = $input["firstname"];
                            } else {
                                $firstname = $user_data["firstname"];
                            }

                            if(isset($input["lastname"])){
                                $lastname = @$input["lastname"];
                            } else {
                                $lastname = $user_data["lastname"];
                            }

                            if(isset($input["password"])){
                                $password = @$input["password"];
                            } else {
                                $password = $user_data["password"];
                            }

                            if(isset($input["address"])){
                                $address = @$input["address"];
                            } else {
                                $address = $user_data["address"];
                            }

                            if(isset($input["phone"])){
                                $phone = @$input["phone"];
                            } else {
                                $phone = $user_data["phone"];
                            }

                            if(isset($input["email"])){
                                $email = @$input["email"];
                            } else {
                                $email = $user_data["email"];
                            }

                            $result = ModifyUser($_GET["index"], $password, $firstname, $lastname, $email, $phone, $address);

                            if($result != null){
                                Response(200, "Modified user", $result);    
                            } else {
                                Response(400, "Modification failure", $result);    
                            }
                        }
                break;
                case "DELETE":
                        $result = DeleteUser($_GET["index"]);

                        if($result == true){
                            Response(200,"Deleted user", true);  
                        } else {
                            Response(404,"Deletion not working", $result);  

                        }
                break;
                default:
                    Response(404, "Not implemented http method", $requestMethod);
                break;
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
            
        case "SharedToMe":
            
            if($_SERVER['REQUEST_METHOD'] == "GET"){
                
                $user = $_GET["index"];
                $events = GetSharedEvents($user);
                
                if(!empty($events)){
                    Response(200, "Shared events found", $events);    
                } else {
                    Response(404, "No shared events found", null);    
                }
                
            } else {
                Response(404, "Invalid http type", null);
            }
            break;
            
        case "SharedEvents":
            
                if($_SERVER['REQUEST_METHOD'] == "GET"){
                
                $user = $_GET["index"];
                $events = GetEventsSharedByUser($user);
                
                if(!empty($events)){
                    Response(200, "Shared events found", $events);    
                } else {
                    Response(404, "No events shared", null);    
                }
                
            } else {
                Response(404, "Invalid http type", null);
            }
            break;
            
        default:
            Response(404, "System failure", null);
            break;
    }
}


function GetInput(){
//script will read x-www-form-urlencoded data
$input = file_get_contents('php://input');
$input = urldecode($input);
$array = explode("&", $input);
$array2 = array();
$parameter = array();
foreach($array as $field){
    $parameter = explode("=", $field);
    $array2[$parameter[0]] = $parameter[1];
}

$input = $array2;
return $input;

//input[firstname] = X
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