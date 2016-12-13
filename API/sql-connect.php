<?php
session_start();
function Connect(){
    try {
        
        
        $host = "localhost";
        $database = "testdatabase";
        $user = "root";
        $password = "";
    
        $conn = new PDO("mysql:host=$host;dbname=$database", $user, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->exec("SET CHARACTER SET utf8");
        return $conn;
        }
    catch(PDOExecption $ex){
        echo $ex->getMessage();
    }
}

/*
//get data from table x
function GetTableData($table){
     try {
        $conn = Connect();
        
        $table = htmlspecialchars($table);
        $statement = $conn->prepare("SELECT * FROM $table;");
        //$statement->bindValue(1, $table, PDO::PARAM_STR);
        $statement->execute();
        $tulos = $statement->fetchAll(PDO::FETCH_ASSOC);;
        return $tulos;

    } catch(PDOException $e){
         return false;
        //echo "error:" . $e->getMessage();
    }
}
*/

function ReadLog($type, $user){
     
    try {
        $conn = Connect();
        
        if($type == "*"){
            $statement = $conn->prepare("Select * From log WHERE targetUser = ? ORDER BY log_timestamp ASC;");    
            $statement->bindValue(1, $user, PDO::PARAM_STR);                
            
        } else {
            $statement = $conn->prepare("Select * From log WHERE targetUser = ? AND type = ? ORDER BY log_timestamp ASC;");
            $statement->bindValue(1, $user, PDO::PARAM_STR);                
            $statement->bindValue(2, $type, PDO::PARAM_STR);

        }
        
        $statement->execute();
        $tulos = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        if(empty($tulos)){
            return false;
        } else {
            return $tulos;
        }
        
    } catch(PDOException $e){
        return false;
    }
}


function CreateLog($type, $desc, $user){
     
    try {
        $conn = Connect();
        $statement = $conn->prepare("INSERT INTO log(type, description, targetUser) VALUE(?, ?, ?);");
                
        $statement->bindValue(1, $type, PDO::PARAM_STR);
        $statement->bindValue(2, $desc, PDO::PARAM_STR);
        $statement->bindValue(3, $user, PDO::PARAM_STR);
        
        $statement->execute(); 
        return true;
        
    } catch(PDOException $e){
        return false;
    }
}

function GetUsers(){
     try {
        $conn = Connect();
        
        $statement = $conn->prepare("SELECT username, firstname, lastname, email, phone, address, 'account-type' FROM user;");
        $statement->execute();
        $tulos = $statement->fetchAll(PDO::FETCH_ASSOC);;
        return $tulos;

    } catch(PDOException $e){
         //echo "error:" . $e->getMessage();
         return false;

    }
}

function GetEvents(){
     try {
        $conn = Connect();
        
        $statement = $conn->prepare("SELECT * FROM event;");
        $statement->execute();
        $tulos = $statement->fetchAll(PDO::FETCH_ASSOC);;
        return $tulos;

    } catch(PDOException $e){ 
        //echo "error:" . $e->getMessage();
        return false;

    }
}

function DoesHeaderExist($header, $owner){
     try {
        $conn = Connect();
        
        $statement = $conn->prepare("SELECT * FROM event WHERE header=? AND owner=?;");
        $statement->bindValue(1, $header, PDO::PARAM_STR);
        $statement->bindValue(2, $owner, PDO::PARAM_STR);
        $statement->execute();
        $tulos = $statement->fetch(PDO::FETCH_ASSOC);;
        
        if(empty($tulos)){
            return false;
        } else {
            return true;
        }
         
    } catch(PDOException $e){ 
        //echo "error:" . $e->getMessage();
        return false;

    }
}



function SearchUsers($target){
    
    $found_users = array();
    $users = GetUsers();
    
    //print_r($usersArray);
    
    foreach($users as $user){
        //print_r($user);
        
        
        $username = false;
        $email = false;
        
        $username = strpos($user["username"], $target);
        $email = strpos($user["email"], $target);
        
        if($username !== false or $email !== false){
            array_push($found_users, $user);  
        }
        
    }
    
    return $found_users;
}

function SearchEvent($header, $user){
    
    try {
        $conn = Connect();
        $event = htmlspecialchars($header);
        $user = htmlspecialchars($user);
        
        $statement = $conn->prepare("SELECT * FROM event where header=? and owner=?;");
        
        $statement->bindValue(1, $header, PDO::PARAM_STR);
        $statement->bindValue(2, $user, PDO::PARAM_STR);
        
        $statement->execute();
        $tulos = $statement->fetchALL(PDO::FETCH_ASSOC);;
        return $tulos;
    } catch(PDOException $e){
        //echo "error:" . $e->getMessage();
    }
    return $found_users;
}



//Get specific event
function GetEventData($event){
     try {
        $conn = Connect();
        $event = htmlspecialchars($event);
        $statement = $conn->prepare("SELECT * FROM event where id=?;");
        $statement->bindValue(1, $event, PDO::PARAM_INT);
        $statement->execute();
        $tulos = $statement->fetch(PDO::FETCH_ASSOC);;
        return $tulos;

    } catch(PDOException $e){
        //echo "error:" . $e->getMessage();
    }
}

//display events by name x made by user y
function GetEventDataHeader($eventheader, $user){
     try {
        $conn = Connect();
        $event = htmlspecialchars($eventheader);
        $statement = $conn->prepare("SELECT * FROM event where header=? and owner=?;");
        $statement->bindValue(1, $eventheader, PDO::PARAM_STR);
        $statement->bindValue(2, $user, PDO::PARAM_STR);
        $statement->execute();
        $tulos = $statement->fetch(PDO::FETCH_ASSOC);;
        return $tulos;

    } catch(PDOException $e){
        //echo "error:" . $e->getMessage();
    }
}

//display event with name made by user
function GetSpecificEventDataHeader($event, $user){
     try {
        $conn = Connect();
        $event = htmlspecialchars($event);
        $statement = $conn->prepare("SELECT * FROM event where header=? and owner=?;");
        $statement->bindValue(1, $event, PDO::PARAM_STR);
        $statement->bindValue(2, $user, PDO::PARAM_STR);
        $statement->execute();
        $tulos = $statement->fetch(PDO::FETCH_ASSOC);;
        return $tulos;

    } catch(PDOException $e){
        //echo "error:" . $e->getMessage();
    }
}

//includes shared events
function UserAllEvents($user){
    
    $combined_array = array();
    $user_made = GetUserEvents($user);
    $shared = GetSharedEvents($user);
    
    array_push($combined_array, $user_made);
    array_push($combined_array, $shared);
    
    
    if($user_made == null and $shared == null){
        return null;
    } else {
        return $combined_array;    
    }
}   


//Create Event for user
function InsertEvent($user, $header, $description, $startDateTime, $endDateTime, $location){
     
    try {
        $conn = Connect();
        
        //starting date must be same or smaller than enddate.
        $sdate = date_parse($startDateTime);
        $edate = date_parse($endDateTime);
        
        if($sdate > $edate){
            return "Event starts after it has already ended";
        }
        //end date validation
        
        $statement = $conn->prepare("INSERT INTO event(header, description, startDateTime, endDateTime, location, owner) VALUES(?,?,?,?,?,?);");
        
        $statement->bindValue(1, $header, PDO::PARAM_STR);
        $statement->bindValue(2, $description, PDO::PARAM_STR);
        $statement->bindValue(3, $startDateTime, PDO::PARAM_LOB);
        
        $statement->bindValue(4, $endDateTime, PDO::PARAM_LOB);
        $statement->bindValue(5, $location, PDO::PARAM_STR);
        $statement->bindValue(6, $user, PDO::PARAM_STR);
        
        $statement->execute();
        
        $statement = $conn->prepare("SELECT id FROM event where header=? and startDateTime=? and owner=? and endDateTime=?;");
        
        $statement->bindValue(1, $header, PDO::PARAM_STR);
        $statement->bindValue(2, $startDateTime, PDO::PARAM_LOB);    
        $statement->bindValue(3, $user, PDO::PARAM_STR);
        $statement->bindValue(4, $endDateTime, PDO::PARAM_LOB);
        $statement->execute();
        
        $tulos = $statement->fetch(PDO::FETCH_ASSOC);;
        
        CreateLog("Create Event", "User created a event: $header", $user);
        
        if($tulos != null){
            return $tulos;
        } else {
            return false;
        }
        

    } catch(PDOException $e){
        return false;
    }
    
}

function DeleteEvent($user, $id){
     
    try {
        $conn = Connect();
        
        $statement = $conn->prepare("DELETE FROM event WHERE id=? and owner=?;");
        
        $statement->bindValue(1, $id, PDO::PARAM_INT);
        $statement->bindValue(2, $user, PDO::PARAM_STR);
        
        $statement->execute();
        
        CreateLog("Delete Event", "User deleted a event with id: $id", $user);
        return true;

    } catch(PDOException $e){
        return $e->getMessage();
    }
    
}


//Create user
function InsertUser($user, $pass, $firstname, $lastname, $email, $phone, $address){
     
    try {
        $conn = Connect();
        
        $statement = $conn->prepare("INSERT INTO user(username, password, firstname, lastname, email, phone, address) VALUES(?,?,?,?,?,?,?);");
        
        $statement->bindValue(1, $user, PDO::PARAM_STR);
        $statement->bindValue(2, $pass, PDO::PARAM_STR);
        $statement->bindValue(3, $firstname, PDO::PARAM_STR);
        
        $statement->bindValue(4, $lastname, PDO::PARAM_STR);
        $statement->bindValue(5, $email, PDO::PARAM_STR);
        $statement->bindValue(6, $phone, PDO::PARAM_STR);
        $statement->bindValue(7, $address, PDO::PARAM_STR);
        
        CreateLog("Create User", "User: $user, was created", $user);
        
        $statement->execute();
        
        return true;

    } catch(PDOException $e){
        return $e->getMessage();
    }
    
}

function DeleteUser($user){
     
    try {
        $conn = Connect();
        
        $statement = $conn->prepare("DELETE FROM user WHERE username=? ;");
        
        $statement->bindValue(1, $user, PDO::PARAM_STR);
        
        CreateLog("Delete user", "User: $user, was deleted", $user);
        
        $statement->execute();
        
        return true;

    } catch(PDOException $e){
        return $e->getMessage();
    }
    
}

//Get events shared to me
function GetSharedEvents($user){
     
    try {

        $conn = Connect();    
        $statement = $conn->prepare("Select id, header, description, startDateTime, endDateTime, location, owner from event INNER JOIN sharedevent on event.id = sharedevent.eventID WHERE username=?;");
                
        $statement->bindValue(1, $user, PDO::PARAM_STR);
        $statement->execute();
        $var = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        return $var;
        
    } catch(PDOException $e){
        return null;
    }
}

function EventSharedToUsers($event_header, $owner){
     
    try {

        $conn = Connect();    
        $statement = $conn->prepare("SELECT username FROM sharedevent INNER JOIN event ON event.id = sharedevent.eventID WHERE event.header = ? AND owner = ?;");
                
        $statement->bindValue(1, $event_header, PDO::PARAM_STR);
        $statement->bindValue(2, $owner, PDO::PARAM_STR);
        $statement->execute();
        $var = $statement->fetchAll(PDO::FETCH_ASSOC);
        if(empty($var)){
            return null;
        } else {
            return $var;
        }
        
    } catch(PDOException $e){
        return false;
    }
}


//Get events that user has made and shared to others
function GetEventsSharedByUser($user){
     
    try {
        $conn = Connect();
        $statement = $conn->prepare("SELECT id, header, description, startDateTime, endDateTime, location, sharedevent.username as sharedToUser from event INNER JOIN sharedevent on event.id = sharedevent.eventID where owner=?");
                
        $statement->bindValue(1, $user, PDO::PARAM_STR);
        $statement->execute();
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        return $results;
        
    } catch(PDOException $e){
        return false;
    }
}



//Get every event from user. Does not include shared events.
function GetUserEvents($user){
     try {
        $conn = Connect();
        $user = htmlspecialchars($user);
        $statement = $conn->prepare("SELECT * FROM event where owner=? ORDER BY startDateTime ASC;");
        
        $statement->bindValue(1, $user, PDO::PARAM_STR);
        $statement->execute();
        
        $tulos = $statement->fetchAll(PDO::FETCH_ASSOC);
        
         /*
        //Start handling shared events
        $shared = GetSharedEvents($user); 
        //return $shared;
        
        if($shared != null and $shared != false){
           foreach($shared as $event){
                array_push($tulos, $event);
           } 
        }
       //End handling shared events
        sort($tulos);
         */
        return $tulos;

    } catch(PDOException $e){
        //echo "error:" . $e->getMessage();
    }
}

//Get specific event from user. Does not include shared events.
function GetUserEvent($user, $id){
     try {
        $conn = Connect();
        $user = htmlspecialchars($user);
        $id = htmlspecialchars($id);
         
        $statement = $conn->prepare("SELECT * FROM event where owner=? and id=?;");
        
        $statement->bindValue(1, $user, PDO::PARAM_STR);
        $statement->bindValue(2, $id, PDO::PARAM_INT);
        $statement->execute();
        
        $tulos = $statement->fetch(PDO::FETCH_ASSOC);;
        
       
        
        return $tulos;

    } catch(PDOException $e){
        //echo "error:" . $e->getMessage();
         return false;
    }
}

function GetSpecificSharedEvent($header, $user){
    try {
        $conn = Connect();
        $user = htmlspecialchars($user);
        $header = htmlspecialchars($header);
         
        $statement = $conn->prepare("SELECT * FROM event INNER JOIN sharedevent ON event.id = sharedevent.eventID where sharedevent.username=? and event.header=?;");
        
        $statement->bindValue(1, $user, PDO::PARAM_STR);
        $statement->bindValue(2, $header, PDO::PARAM_INT);
        $statement->execute();
        
        $tulos = $statement->fetchAll(PDO::FETCH_ASSOC);;
        
        return $tulos;

    } catch(PDOException $e){
        //echo "error:" . $e->getMessage();
         return false;
    }
}
 
 //Get events from certain timeframe
function GetEventsDefTime($user, $start_inp, $end_inp, $param){
     try {
        $conn = Connect();
        $user = htmlspecialchars($user);
        $start = htmlspecialchars($start_inp);
        $end = htmlspecialchars($end_inp);
        
         switch($param){
             case "all":
                  $statement = $conn->prepare("SELECT * FROM event WHERE event.startDateTime >= ? AND event.endDateTime <=  ? AND owner = ? ORDER BY event.startDateTime ASC;");
                 
                    $statement->bindValue(1, $start, PDO::PARAM_STR);
                    $statement->bindValue(2, $end, PDO::PARAM_STR);
                    $statement->bindValue(3, $user, PDO::PARAM_STR);
                 
                  $statement->execute();
                  $tulos_1 = $statement->fetchAll(PDO::FETCH_ASSOC);
                 
                  $statement = $conn->prepare("SELECT * FROM event INNER JOIN sharedevent ON event.id = sharedevent.eventID WHERE event.startDateTime >= ? AND event.endDateTime <=  ? AND sharedevent.username = ? ORDER BY event.startDateTime ASC");
                    $statement->bindValue(1, $start, PDO::PARAM_STR);
                    $statement->bindValue(2, $end, PDO::PARAM_STR);
                    $statement->bindValue(3, $user, PDO::PARAM_STR);
                  $statement->execute();
                  $tulos_2 = $statement->fetchAll(PDO::FETCH_ASSOC);
                 
                  if(empty($tulos_1)){
                      return $tulos_1;
                  } else {
                      foreach($tulos_1 as $result){
                          array_push($tulos_2, $result);
                      }
                      
                      return $tulos_2;
                  }
                 
                 break;
             case "shared":
                 $statement = $conn->prepare("SELECT * FROM event INNER JOIN sharedevent ON event.id = sharedevent.eventID WHERE event.startDateTime >= ? AND event.endDateTime <=  ? AND sharedevent.username = ? ORDER BY event.startDateTime ASC");
                    $statement->bindValue(1, $start, PDO::PARAM_STR);
                    $statement->bindValue(2, $end, PDO::PARAM_STR);
                    $statement->bindValue(3, $user, PDO::PARAM_STR);
                 $statement->execute();
                 $tulos_2 = $statement->fetchAll(PDO::FETCH_ASSOC);
                 return $tulos_2;
                 break;
             case "own":
                 $statement = $conn->prepare("SELECT * FROM event WHERE event.startDateTime >= ? AND event.endDateTime <=  ? AND owner = ? ORDER BY event.startDateTime ASC;");
                    $statement->bindValue(1, $start, PDO::PARAM_STR);
                    $statement->bindValue(2, $end, PDO::PARAM_STR);
                    $statement->bindValue(3, $user, PDO::PARAM_STR);
                  $statement->execute();
                  $tulos_1 = $statement->fetchAll(PDO::FETCH_ASSOC);
                 return $tulos_1;
                 break;
             default:
                 return "error!";
                 break;
         }

    } catch(PDOException $e){
        echo "error:" . $e->getMessage();
         return false;
    }
}   
    
    
function ShareEvent($eventID, $user){
    try {
        
        $conn = Connect();
        
        $conn->beginTransaction();
        
        
        //first empty then reenter
        $statement = $conn->prepare("DELETE FROM sharedevent WHERE eventID = ?;");
        
        $statement->bindValue(1, $eventID, PDO::PARAM_INT);
        
        $statement->execute();
        
        $statement = $conn->prepare("INSERT INTO sharedevent(eventID, username) VALUES(?,?);");
        
        $statement->bindValue(1, $eventID, PDO::PARAM_INT);
        $statement->bindValue(2, $user, PDO::PARAM_STR);
        
        $statement->execute();
        
        CreateLog("Share event", "Event $eventID was shared to user $user", $user);
        $conn->commit();
        return true;

    } catch(PDOException $e){
        //echo "error:" . $e->getMessage();
        $conn->rollBack();
        return false;
        
    }
}

//Delete old replace new
function ShareReplace($eventID, $users){
    try {
        
        $conn = Connect();
        
        $conn->beginTransaction();
        
        //first empty then reenter
        $statement = $conn->prepare("DELETE FROM sharedevent WHERE eventID = ?;");
        
        $statement->bindValue(1, $eventID, PDO::PARAM_INT);
        
        $statement->execute();
        if(count($users) == 0){
            
        } else {
            foreach($users as $user){
                $statement = $conn->prepare("INSERT INTO sharedevent(eventID, username) VALUES(?,?);");

                $statement->bindValue(1, $eventID, PDO::PARAM_INT);
                $statement->bindValue(2, $user, PDO::PARAM_STR);

                $statement->execute();
            }
        }
        
        $conn->commit();
        return true;

    } catch(PDOException $e){
        //return "error:" . $e->getMessage();
        $conn->rollBack();
        return false;
        
    }
}

function UnShareEvent($eventID, $user){
    try {
        $conn = Connect();
        $statement = $conn->prepare("DELETE from sharedevent where eventID=? and username=?;");
        
        $statement->bindValue(1, $eventID, PDO::PARAM_INT);
        $statement->bindValue(2, $user, PDO::PARAM_STR);
        
        $statement->execute();
        CreateLog("UnShare event", "event $eventID no longer shared to user $user", $user);
        return true;

    } catch(PDOException $e){
        //echo "error:" . $e->getMessage();
         return false;
    }
}

function ModifyUser($username, $password, $firstname, $lastname, $email, $phone, $address){
    try {
        $conn = Connect();
        $statement = $conn->prepare("UPDATE user SET password=?, firstname=?, lastname=?, email=?, phone=?, address=? WHERE username=?;");
        
        $statement->bindValue(1, $password, PDO::PARAM_STR);
        $statement->bindValue(2, $firstname, PDO::PARAM_STR);
        $statement->bindValue(3, $lastname, PDO::PARAM_STR);
        $statement->bindValue(5, $phone, PDO::PARAM_STR);
        $statement->bindValue(6, $address, PDO::PARAM_STR);
        $statement->bindValue(4, $email, PDO::PARAM_STR);
        $statement->bindValue(7, $username, PDO::PARAM_STR);
        
        $statement->execute();
        CreateLog("Modify user", "$username was modified", $username);
        return GetUserData($username);

    } catch(PDOException $e){
        //echo "error:" . $e->getMessage();
         return false;
    }
}

//Modify event
function ModifyUserEvent($header, $desc, $Start, $Ends, $location, $owner, $id){
    try {
        $conn = Connect();
        $statement = $conn->prepare("UPDATE event SET header=?, description=?, startDateTime=?, endDateTime=?, location=? WHERE owner=? AND id=?");
        
        $statement->bindValue(1, $header, PDO::PARAM_STR);
        $statement->bindValue(2, $desc, PDO::PARAM_STR);
        $statement->bindValue(3, $Start, PDO::PARAM_STR);
        $statement->bindValue(4, $Ends, PDO::PARAM_STR);
        $statement->bindValue(5, $location, PDO::PARAM_STR);
        $statement->bindValue(6, $owner, PDO::PARAM_STR);
        $statement->bindValue(7, $id, PDO::PARAM_STR);
        
        $statement->execute();
        
        CreateLog("Modify Event", "Event $header was modified", $owner);
        return true;

    } catch(PDOException $e){
        //echo "error:" . $e->getMessage();
         return $e;
    }
}

function GetEventId($header, $owner){
    try {
        $conn = Connect();
        $statement = $conn->prepare("SELECT id FROM event WHERE owner=? AND header=?");
        
        $statement->bindValue(1, $owner, PDO::PARAM_STR);
        $statement->bindValue(2, $header, PDO::PARAM_STR);
        
        $statement->execute();
        $tulos = $statement->fetch(PDO::FETCH_ASSOC);
        
         if(empty($tulos)){
            return false;
        } else {
            return $tulos;    
        }

    } catch(PDOException $e){
        //echo "error:" . $e->getMessage();
         return false;
    }
}

//Get user profile
function GetUserData($user){
     try {
        $conn = Connect();
        $user = htmlspecialchars($user);
        $statement = $conn->prepare("SELECT username, password, firstname, lastname, phone, email, address FROM user where username=?;");
        $statement->bindValue(1, $user, PDO::PARAM_STR);
        $statement->execute();
        $tulos = $statement->fetch(PDO::FETCH_ASSOC);
         
         if(empty($tulos)){
            return false;
        } else {
            return $tulos;    
        }

    } catch(PDOException $e){
        //echo "error:" . $e->getMessage();
         return false;
    }
}

function Login($user, $pass){
    try {
        
        $conn = Connect();
        $user = htmlspecialchars($user);
        $pass = htmlspecialchars($pass);
        $pass = sha1($pass);
        $statement = $conn->prepare("SELECT username, password FROM user where username=? and password=?;");
        $statement->bindValue(1, $user, PDO::PARAM_STR);
        $statement->bindValue(2, $pass, PDO::PARAM_STR);
        $statement->execute();
        $tulos = $statement->fetch(PDO::FETCH_ASSOC);;
        
        CreateLog("Login", "User logged in", $user);
        
        if(empty($tulos)){
            return false;
        } else {
            return $tulos;    
        }
        
        
    } catch(PDOException $e){
        //echo "error:" . $e->getMessage();
         return false;
    }
}

function DoesUserHaveToken($user){
    try {
        $conn = Connect();
        $user = htmlspecialchars($user);
        
        $statement = $conn->prepare("Select token from useraccess where username = ?;");
        $statement->bindValue(1, $user, PDO::PARAM_STR);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        
        if(empty($result)){
            return false;
        } else {
            return true;
        }
        
    } catch(PDOException $e){
        //echo "error:" . $e->getMessage();
         return false;
    }
}

function isValidToken($token){
    try {
        $user = $_COOKIE["user"];
        $conn = Connect();
        
        $statement = $conn->prepare("Select token from useraccess where token = ? and username = ?;");
        $statement->bindValue(1, $token, PDO::PARAM_STR);
        $statement->bindValue(2, $user, PDO::PARAM_STR);
        
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        
        if(empty($result)){
            return false;
        } else {
            return true;
        }
        
    } catch(PDOException $e){
        //echo "error:" . $e->getMessage();
         return false;
    }
}

function isUserAdmin($username){
    try {
        $conn = Connect();
        $statement = $conn->prepare("Select account-type from useraccess where username = ?;");
        $statement->bindValue(1, htmlspecialchars($username), PDO::PARAM_STR);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        
        if(empty($result) or $result != "Admin"){
            return false;
        } else {
            return true;
        }
        
    } catch(PDOException $e){
        //echo "error:" . $e->getMessage();
         return false;
    }
}

function Logout($token){
    try {
        $conn = Connect();
        $statement = $conn->prepare("DELETE from useraccess where token = ?;");
        $statement->bindValue(1, $token, PDO::PARAM_STR);
        $statement->execute();
        
        $user = $_COOKIE["user"];
        setcookie("token", "", time() - 3600);
        setcookie("user", "", time() - 3600);
        
        CreateLog("Logout", "User logged out", $user);
        
        return true;

    } catch(PDOException $e){
        //echo "error:" . $e->getMessage();
         return false;
    }
}



function CreateAccessToken($user){
    try {
        
        $date = new DateTime("NOW");
        $dateformat = $date->format("Y-m-d H:m:s");
        $encrypted = sha1($dateformat);
        
        $conn = Connect();
        $user = htmlspecialchars($user);
        
        if(DoesUserHaveToken($user) == true){
            $statement = $conn->prepare("Update useraccess set token = ?, createdToken = ? where username = ?;");
            $statement->bindValue(1, $encrypted, PDO::PARAM_STR);
            $statement->bindValue(2, $dateformat, PDO::PARAM_STR);
            $statement->bindValue(3, $user, PDO::PARAM_STR);
            $statement->execute();
            
            //token contains the access token for one day;
            setcookie("token", $encrypted, time()+(3600*24), "/");
            
            setcookie("user", $user, time()+(3600*24), "/");
            
            return $encrypted;
        } else {

            $statement = $conn->prepare("INSERT INTO useraccess(username, token, createdToken) VALUES(?,?,?);");
            $statement->bindValue(1, $user, PDO::PARAM_STR);
            $statement->bindValue(2, $encrypted, PDO::PARAM_STR);
            $statement->bindValue(3, $dateformat, PDO::PARAM_STR);
            $statement->execute();
            
            //token contains the access token for one day;
            setcookie("token", $encrypted, time()+(3600*24), "/");
            
            setcookie("user", $user, time()+(3600*24), "/");
            
            
            return $encrypted;
            
        }
    } catch(PDOException $e){
        echo "error:" . $e->getMessage();
         return false;
    }
}

?>
