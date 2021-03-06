<?php
require_once("sql-connect.php");

/*
http://www.restapitutorial.com/lessons/httpmethods.html
*/

//200 = ok, 400 = no
/*
$uri = explode("API/",$_SERVER["REQUEST_URI"]);
echo json_encode($uri);
*/


if(empty($_GET["type"])){
    //Jos selataan selaimella apia ilman määrittämättä mitä etsitään.
    Response(400,"Response fail, no data input", null);
} else {
    
    
    
    
    
    
    /*
    
    if(isUserAdmin($_COOKIE["user"]) == false){
    	Response("404", "You must be a admin to use this feature", "null");
    } else {
    
    }
    */    
    
    if(empty($_GET["apikey"]) == true){
        
        
        switch($_GET["type"]){
            case "login":
                break;
            case "user":
                break;
            case "search_user":
                
                break;
            default:
                Response(404, "For creating a user, user POST header", null);
                die();
                break;
        }
        
    } else {
    //Jos käyttäjä on kirjautunut sisään
    if(isValidToken($_GET["apikey"]) == false){
        Response(400, "Incorrect apikey. You have to relogin", null);
        die();
    }
    }
    
    $parameter;

    switch($_GET["type"]){
        
        case "log":
            //ReadLog($type, $user)
            $requestMethod = $_SERVER['REQUEST_METHOD'];
            $user = htmlspecialchars($_GET["user"]);
            $type = htmlspecialchars($_GET["logtype"]);
            
            //hakeeko käyttäjä omia tietojaan?
            if(isAuthorized($user) != true){
                die();
            }
                
            $readLog = ReadLog($type, $user);
            
            if($readLog == false){
                Response(404, "No logs found", $user . $type);
            } else {
                Response(200, "Logs found", $readLog);
            }
            break;
        case "search_event":
            $requestMethod = $_SERVER['REQUEST_METHOD'];
            $user = htmlspecialchars($_GET["user"]);
            $header = htmlspecialchars($_GET["header"]);
            $found_event = SearchEvent($header, $user);
            
             //hakeeko käyttäjä omia tietojaan?
            if(isAuthorized($user) != true){
                die();
            }
            
            if(empty($found_event)){
                Response(404, "No event found", null);
            } else {
                Response(200, "Event found", $found_event);
            }
            
            break;
        
        case "search_user":
            //username or email to be found
            $index = $_GET["param"];
            
            $users = SearchUsers($index);
            $type = $_GET["search_type"];
            
            
            if(count($users) == 0){
                Response(404, "User not found", null);
            } else {
                $list = array();
                
                if($type == "list"){
                    foreach($users as $user){
                        array_push($list, $user["username"]);
                    }
                    
                    Response(200, "Users found", $list);
                    
                } else {
                    Response(200, "Users found", $users);
                }
                
                
            }
            
            break;
        case "login":
            $requestMethod = $_SERVER['REQUEST_METHOD'];
            // When creating event index = header, else = id         
            
            switch($requestMethod){
                case "GET":
                    
                    $token   =   $_COOKIE["token"];
                    
                    if(empty($token)){
                        Response(400, "You have to login", null);
                    } else {
                        if(isValidToken($token) == true){
                            Response(200, "You are logged in", null);
                        } else {
                            Response(404, "Incorrect token. Login again", null);
                        }   
                    }
                    
                    break;
                    case "DELETE":
                    
                    $token   =   $_COOKIE["token"];
                    
                    if(empty($token)){
                        Response(400, "You are already logged out", null);
                    } else {
                        if(Logout($token) == true){
                            Response(200, "You have logged out succefully", null);
                        } else {
                            Response(404, "Logging out failed", null);
                        }   
                    }
                    
                    break;
                    case "POST":
                    
                    $user   =   $_GET["username"];
                    $pass   =   $_POST["password"];
                    $success = Login($user, $pass);
                    
                    if($success == true){
                        //get access token
                        
                        $token = CreateAccessToken($user);
                        if(empty($token)){
                            Response(400, "Token creation failed", $token);
                        } else {
                            Response(200, "Token created", "$token");
                        }
                    } else {
                        Response(400, "Username or password incorrect", null);
                    }
                    
                    break;
            }
            break;
        case "eventID":
            //index.php?type=eventID&user=$1&eventheader=$2&apikey=$3
            $requestMethod = $_SERVER['REQUEST_METHOD'];
            
            $user = $_GET["user"];
            $header = $_GET["eventheader"];
            $token = $_GET["apikey"];
            
            
            if($requestMethod == "GET"){
              $id = GetEventId($header, $user);
              if($id === false){
                Response(404, "Event not found", $header);  
              } else {
                Response(200, "Found event id", $id);      
              }
            
            } else {
                Response(400, "Incorrect http header", null);
            }
            
            //GetEventId
            break;
        case "userEvent":
            //API/users/{username}/events/{eventid}
            $requestMethod = $_SERVER['REQUEST_METHOD'];
            // When creating event index = header, else = id         
            
            switch($requestMethod){
                case "GET":
                    
                    $user = htmlspecialchars($_GET["user"]);
                    //hakeeko käyttäjä omia tietojaan?
                    if(isAuthorized($user) != true){
                        die();
                    }
                    
                    if(is_int($_GET["index"])){
                        $parameter = GetUserEvent($_GET["user"], $_GET["index"]);    
                    } else {
                        $parameter = GetEventDataHeader($_GET["index"], $_GET["user"]);
                    }
 
                    if($parameter == false){
                        Response(400, "Event does not exist", null);
                    } else {
                        Response(200, "Response ok", $parameter);    
                    }
                    
                    break;
                case "POST":
                    //create event for user. index = username
		            
                    
                    
			        $user = htmlspecialchars($_GET["user"]);
                    //hakeeko käyttäjä omia tietojaan?
                    if(isAuthorized($user) != true){
                        die();
                    }
			    
                    //index = otsikko
                    if(isset($_GET["user"]) and isset($_POST["startdatetime"]) and isset($_POST["enddatetime"])) {

                        //all required data exists
                        //validate input
                        if(!empty($_GET["index"])){
                            $header = $_GET["index"];
                        } else {
                            $header = "";
                        }

                        if(!empty($_POST["description"])){
                            $desc = $_POST["description"];
                        } else {
                            $desc = "";
                        }


                        $EventStart = new DateTime($_POST["startdatetime"]);
                        $FormatStart = $EventStart->format("Y-m-d H:i:s");

                        $EventEnd =  new DateTime($_POST["enddatetime"]);
                        $FormatEnd = $EventEnd->format("Y-m-d H:i:s");

                        if(!empty($_POST["location"])){
                            $location = $_POST["location"];
                        } else {
                            $location = "";
                        }

                        $response = InsertEvent($_GET["user"], $header, $desc, $FormatStart, $FormatEnd, $location);

                        if($response !== false){
                            Response(200, "New event created", $response);
                        } else {
                            Response(404, "Creating event failed", $response);    
                        }

                        } else {
                        Response(404, "Missing required data", null);    
                        }  
                    break;
                    
                case "PUT":
			 
			         $user = htmlspecialchars($_GET["user"]);
                    //hakeeko käyttäjä omia tietojaan?
                    if(isAuthorized($user) != true){
                        die();
                    }
                    
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
                            
                        $id = GetEventId($header, $owner);
                        if($id === false){
                            
                            Response(400, "Modification failure", "id not found"); 
                            
                        } else {
                            
                            //if any changes are detected.
                            if($counter != 0){
                            $result = ModifyUserEvent($_GET["index"], $header, $description, $startDateTime, $endDateTime, $location, $owner, $id);
                            } else {
                                Response(400, "No changes defined", null);
                            }

                            if($result === true){
                                Response(200, "Modified user", GetUserEvent($_GET["user"], $_GET["index"]));    
                            } else {
                                Response(400, "Modification failure", $result);    
                            }
                        }
                    }
                    }
                     
                    break;
            
                case "DELETE":
			  
			             
                        $user = htmlspecialchars($_GET["user"]);
                    //hakeeko käyttäjä omia tietojaan?
                    if(isAuthorized($user) != true){
                        die();
                    }
                    
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
		    
            $user = $_COOKIE["user"];
		    //admin=?
		    if(isUserAdmin($user) != true){
                Response(404, "You are not a admin", null);
                die();
            }
            
            if($_SERVER['REQUEST_METHOD'] == "GET"){
                $parameter = GetEvents();
                
                if($parameter == null){
                    Response(404, "No events defined", null);
                } else {
                    Response(200, "Events found", $parameter);
                }
                
            }
            break;
        
            
        case "userEvents":
		
                $user = htmlspecialchars($_GET["index"]);
                //hakeeko käyttäjä omia tietojaan?
                if(isAuthorized($user) != true){
                    die();
                }
            
            
                $parameter = GetUserEvents($_GET["index"]);

                if($parameter == null){
                    Response(404, "No events defined", null);
                } else {
                    Response(200, "Events found", $parameter);
                }
		break;
     
   	case "user":
            
            $requestMethod = $_SERVER['REQUEST_METHOD'];
            
            // When creating event index = header, else = id         
            
            $user = htmlspecialchars($_GET["index"]);
            
            //hakeeko käyttäjä omia tietojaan?
            /*
            if(isAuthorized($user) != true and $requestMethod =! "POST"){
                die();
            }
            */
            
            switch($requestMethod){
                case "GET":
                    $parameter = GetUserData($_GET["index"]);

                    if($parameter === false){
                        Response(400, "User does not exist", null);
                    } else {
                        Response(200, "Response ok", $parameter);    
                    }
                break;
                    
                case "POST":
                        
                        $username = $_POST["username"];
                        $error = 0;
                        $error_list = array();
                         
                    if(empty($username) or empty($_POST["password"]) or empty($_POST["email"])){
                            Response(404,"username, password, firstname, lastname and email have to defined.", false);
                        } 
                        else {
                            
                            if(!empty($_POST["password"])){
                                    //! ENCRYPTAUS
                                    $password = sha1($_POST["password"]);
                                     //[A-ZÖÄÅ].[a-zöäå]+    
                                    $valid = preg_match("/.{8,25}/", $_POST["password"]);
                                    if($valid == false){
                                        array_push($error_list, "Password must be 8 - 25 long");
                                        $error++;
                                    }
                                    
                            } else {
                                    Response(404, "password not defined", null);
                            }

                            if(!empty($_POST["firstname"])){
                                    $firstname = $_POST["firstname"];
                                
                                    $valid = preg_match("/[a-zA-ZöäåÖÄÅ\-]+/", $firstname);
                                    if($valid == false){
                                        array_push($error_list, "firstname must be only letters. Can contain letter -");
                                        $error++;
                                    }
                                
                                } else {
                                    $firstname = "";
                            }

                            if(!empty($_POST["lastname"])){
                                    $lastname = $_POST["lastname"];
                                    $valid = preg_match("/[a-zA-ZöäåÖÄÅ\-]+/", $lastname);
                                    if($valid == false){
                                        array_push($error_list, "lastname must be only letters. Can contain letter -");
                                        $error++;
                                    }
                                } else {
                                    $lastname = "";
                            }

                            if(!empty($_POST["email"])){
                                    $email = $_POST["email"];
                                    $valid = preg_match("/^[a-zA-ZöäåÖÄÅ0-9]+@[a-zA-ZöäåÖÄÅ0-6]+\.[a-zA-ZöäåÖÄÅ0-6]+/", $email);
                                    if($valid == false){
                                        array_push($error_list, "email is not in valid format");
                                        $error++;
                                    }
                                } else {
                                    Response(404, "email not defined", null);
                            }

                            if(!empty($_POST["phone"])){
                                    $phone = $_POST["phone"];
                                    $valid = preg_match("/^[0-9 \-+]+$/", $phone);
                                    if($valid == false){
                                        array_push($error_list, "phonenumber is in incorrect format. Can contain numbers or whitespace or -");
                                        $error++;
                                    }
                                } else {
                                    $phone = "";                }

                            if(!empty($_POST["address"])){
                                    $address = $_POST["address"];
                                    $valid = preg_match("/[a-zA-ZöäåÖÄÅ0-9 ]+/", $firstname);
                                    if($valid == false){
                                        array_push($error_list, "Address in incorrect format. Can contain letters, numbers and whitespaces");
                                        $error++;
                                    }
                                } else {
                                    $address = "";
                            }
                                if($error == 0){
                                    $respond = InsertUser($username, $password, $firstname, $lastname, $email, $phone, $address);
                                    } else {
                                    
                                        $response = false;
                                        Response(400, "Error input not correct", $error_list);
                                        die();    
                                    }
                            
                                    if($respond === true){
                                        Response(200, "Response ok", $respond);
                                    } else {
                                        Response(400, "Creating user failed", $respond);
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
                                $lastname = $input["lastname"];
                            } else {
                                $lastname = $user_data["lastname"];
                            }

                            if(isset($input["password"])){
                                $password = $input["password"];
                            } else {
                                $password = $user_data["password"];
                            }

                            if(isset($input["address"])){
                                $address = $input["address"];
                            } else {
                                $address = $user_data["address"];
                            }

                            if(isset($input["phone"])){
                                $phone = $input["phone"];
                            } else {
                                $phone = $user_data["phone"];
                            }

                            if(isset($input["email"])){
                                $email = $input["email"];
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
		    
            $user = $_COOKIE["user"];
		    //admin=?
		    if(isUserAdmin($user) != true){
                Response(404, "You are not a admin", null);
                die();
            } 
                
            //API/users/
            if($_SERVER['REQUEST_METHOD'] == "GET"){
                
                $parameter = GetUsers();
                
                if($parameter == null){
                    Response(404, "No users defined", null);
                } else {
                    Response(200, "Response ok", $parameter);
                }
            }
            
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                Response(404 ,"Not implemented", null);
            }
            
            break;
            
        case "EventsSharedTo":
            $user = htmlspecialchars($_GET["index"]);
            //hakeeko käyttäjä omia tietojaan?
            if(isAuthorized($user) != true){
                die();
            }
		    
            if($_SERVER['REQUEST_METHOD'] == "GET"){
                //user we want
                $event_header = $_GET["header"];
                $usernames = EventSharedToUsers($event_header, $user);
                
                if(empty($usernames)){
                    Response(404, "Event is not shared to anyone", null);    
                } elseif($usernames !== false) {
                    Response(200, "Shared events found", $usernames);
                } else {
                    Response(400, "Something went wrong", null);
                }
                
            } else {
                Response(404, "Invalid http type", null);
            }
            break;
            case "SharedToMe":
           
            $user = htmlspecialchars($_GET["index"]);
                //hakeeko käyttäjä omia tietojaan?
                if(isAuthorized($user) != true){
                    die();
                }
		    
            if($_SERVER['REQUEST_METHOD'] == "GET"){
                //user we want
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
            
		  $user = htmlspecialchars($_GET["index"]);
            //hakeeko käyttäjä omia tietojaan?
            if(isAuthorized($user) != true){
                die();
            }
		    
                if($_SERVER['REQUEST_METHOD'] == "GET"){
                
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
            
        case "eventSpef":
            
            
                $user = htmlspecialchars($_GET["user"]);
                //hakeeko käyttäjä omia tietojaan?
                if(isAuthorized($user) != true){
                    die();
                }
            
            if($_SERVER['REQUEST_METHOD'] == "GET"){
                //index.php?type=eventSpef&user=$1&start=$2&end=$3search_type=$3&apikey=$4
                $start = $_GET["start"];
                $end = $_GET["end"];
                $type = $_GET["search_type"];
                
                
                switch($type){
                    case "all":
                        $events = GetEventsDefTime($user, $start, $end, "all");
                        break;
                    case "shared":
                        $events = GetEventsDefTime($user, $start, $end, "shared");
                        break;
                    case "own":
                        $events = GetEventsDefTime($user, $start, $end, "own");
                        break;
                    default:
                        if($type == null){
                            $events = GetEventsDefTime($user, $start, $end, "all");    
                        } else {
                            Response("400", "Invalid search_type", false);
                        }
                        
                        break;
                }
                
  
                if($events === false or empty($events)){
                    Response("404", "No events found", null);
                } else {
                    Response("200", "Events found", $events);
                }

            } else {
                Response("400", "Invalid http header", false);
            }
            break;
        case "Share":
			
            //event id = eventid, user whos event is shared 
            //hakeeko käyttäjä omia tietojaan?
            if(isAuthorized($_COOKIE["user"]) != true){
                die();
            }
            
            $eventID = $_GET["eventid"];
			$httpMethod = $_SERVER['REQUEST_METHOD'];
            $target_user = $_GET["selecteduser"];
                        
			switch($httpMethod){
				case "POST":
					
					if(!empty($target_user)){
						if(ShareEvent($eventID, $target_user) == true){
							Response(200, "Event shared with $target_user", null);
						}
						else {
							Response(400, "sharing the event failed", null);
						}
					} else {
						Response(404, "Input incorrect", null);
					}
				break;
                    
                case "PUT":
					$eventids = GetInput();
                    /*
					if(!empty($target_user)){
						if(ShareEvent($eventID, $target_user) == true){
							Response(200, "Event shared with $target_user", null);
						}
						else {
							Response(400, "sharing the event failed", null);
						}
					} else {
						Response(404, "Input incorrect", null);
					}
                    */
                    Response(404, "Input incorrect", $eventids);
				break;
                    
				case "DELETE":
					if(!empty($target_user)){
						if(UnShareEvent($eventID, $target_user) == true){
							Response(200, "Event no longer shared with $target_user", null);
						}
						else {
							Response(400, "sql error, removing event failed", null);
						}
					} else {
						Response(404, "Input incorrect", null);
					}
				break;
				default:
					Response(404, "Invalid http method", $httpMethods);
				break;
			}
			
            break;
        default:
            Response(404, "System failure", null);
            break;
    }
    }
//} //end of "if you are logged in"


/*
reg_match
//username letters and numbers 
//password any min 8 max X
//firstname letters or -
//lastname letters
//email XX.X
//phone numbers and optional + at start
//address


*/



//used to read input when using http method PUT
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

function isAuthorized($username){
    $user = $_COOKIE["user"];
    $token = $_COOKIE["token"];
    
    $username = htmlspecialchars($username);
    $user = htmlspecialchars($user);
    
    if($user == $username or isUserAdmin($user)){
        return true;
    } else {
        Response(404, "You have no access to this element", false);
        return false;
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
