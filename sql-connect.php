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
        
        return true;

    } catch(PDOException $e){
        return $e->getMessage();
    }
    
}

function DeleteEvent($user, $id){
     
    try {
        $conn = Connect();
        
        $statement = $conn->prepare("DELETE FROM event WHERE id=? and owner=?;");
        
        $statement->bindValue(1, $id, PDO::PARAM_INT);
        $statement->bindValue(2, $user, PDO::PARAM_STR);
        
        $statement->execute();
        
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
        
        $statement->execute();
        
        return true;

    } catch(PDOException $e){
        return $e->getMessage();
    }
    
}

function GetSharedEvents($user){
     
    try {

        $conn = Connect();    
        $statement = $conn->prepare("Select id, header, startDateTime, endDateTime, location, owner from event INNER JOIN sharedevent on event.id=sharedevent.eventID WHERE username=?;");
                
        $statement->bindValue(1, $user, PDO::PARAM_STR);
        $statement->execute();
        $var = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        return $var;
        
    } catch(PDOException $e){
        return null;
    }
}

function GetEventsSharedByUser($user){
     
    try {
        $conn = Connect();
        $statement = $conn->prepare("SELECT id, header, startDateTime, endDateTime, location, sharedevent.username as sharedToUser from event INNER JOIN sharedevent on event.id = sharedevent.eventID where owner=?");
                
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
         
        $statement = $conn->prepare("SELECT * FROM event where owner=?;");
        
        $statement->bindValue(1, $user, PDO::PARAM_STR);
        $statement->execute();
        
        $tulos = $statement->fetchAll(PDO::FETCH_ASSOC);
        
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

function ShareEvent($eventID, $user){
    try {
        $conn = Connect();
        $statement = $conn->prepare("INSERT INTO sharedevent(eventID, username) VALUES(?,?);");
        
        $statement->bindValue(1, $eventID, PDO::PARAM_INT);
        $statement->bindValue(2, $user, PDO::PARAM_STR);
        
        $statement->execute();
        
        return true;

    } catch(PDOException $e){
        //echo "error:" . $e->getMessage();
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
        
        return GetUserData($username);

    } catch(PDOException $e){
        //echo "error:" . $e->getMessage();
         return false;
    }
}

//Modify event
function ModifyUserEvent($id, $header, $desc, $Start, $Ends, $location, $owner){
    try {
        $conn = Connect();
        $statement = $conn->prepare("UPDATE event SET header=?, description=?, startDateTime=?, endDateTime=?, location=?, owner=? WHERE id=?;");
        
        $statement->bindValue(1, $header, PDO::PARAM_STR);
        $statement->bindValue(2, $desc, PDO::PARAM_STR);
        $statement->bindValue(3, $Start, PDO::PARAM_STR);
        $statement->bindValue(4, $Ends, PDO::PARAM_STR);
        $statement->bindValue(5, $location, PDO::PARAM_STR);
        $statement->bindValue(6, $owner, PDO::PARAM_STR);
        $statement->bindValue(7, $id, PDO::PARAM_INT);
        
        $statement->execute();
        
        return true;

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
        $tulos = $statement->fetch(PDO::FETCH_ASSOC);;
        return $tulos;

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
        
        
        $statement = $conn->prepare("SELECT username, password FROM user where username=? and password=?;");
        $statement->bindValue(1, $user, PDO::PARAM_STR);
        $statement->bindValue(2, $pass, PDO::PARAM_STR);
        $statement->execute();
        $tulos = $statement->fetch(PDO::FETCH_ASSOC);;
        
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
        $conn = Connect();
        $statement = $conn->prepare("Select token from useraccess where token = ?;");
        $statement->bindValue(1, $token, PDO::PARAM_STR);
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
        $statement->bindValue(1, $username, PDO::PARAM_STR);
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
        setcookie("token", "", time() - 3600);
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
            $_SESSION["token"] = $encrypted;
            $statement->execute();
            
            setcookie("token", $encrypted, time()+(1000), "/");
            return $encrypted;
        } else {

            $statement = $conn->prepare("INSERT INTO useraccess(username, token, createdToken) VALUES(?,?,?);");
            $statement->bindValue(1, $user, PDO::PARAM_STR);
            $statement->bindValue(2, $encrypted, PDO::PARAM_STR);
            $statement->bindValue(3, $dateformat, PDO::PARAM_STR);
            $statement->execute();
            $_SESSION["token"] = $encrypted;
            //setcookie("token", $encrypted, time()+(1000), "/");
            return $encrypted;
            
        }
    } catch(PDOException $e){
        echo "error:" . $e->getMessage();
         return false;
    }
}

/*
function ShowGroup($id){
    
    try {
        $conn = Connect();
        $statement = $conn->prepare("SELECT * FROM group_table where id=?;");
        $statement->bindValue(1, $id, PDO::PARAM_INT);
        $statement->execute();
    
        $tulos = $statement->fetch(PDO::FETCH_ASSOC);;
        return $tulos;
    } catch (PDOException $e){
        return false;
    }
}

function ModifyGroup($id, $name, $owner){
        try{
        $conn = Connect();
        $user = htmlspecialchars($user);
        $statement = $conn->prepare("UPDATE group_table set groupName=?, owner=? where id=?;");
        
        $statement->bindValue(1, $name, PDO::PARAM_STR);
        $statement->bindValue(2, $owner, PDO::PARAM_STR);
        $statement->bindValue(3, $id, PDO::PARAM_INT);
        
        $statement->execute();
        return "Success";
            
        } catch(PDOException $e){
            return false;
        }
}

function CreateGroup($name, $owner){
    try {
        $conn = Connect();
        $user = htmlspecialchars($user);
        $statement = $conn->prepare("INSERT INTO group(groupName, creationDate, owner) VALUES(?,?,?);");
        
        $statement->bindValue(1, $name, PDO::PARAM_STR);
        $statement->bindValue(2, date("Y-m-d"), PDO::PARAM_STR);
        $statement->bindValue(3, $owner, PDO::PARAM_STR);
        
        $statement->execute();
        $tulos = $statement->fetch(PDO::FETCH_ASSOC);;
        return $tulos;

    } catch(PDOException $e){
        //echo "error:" . $e->getMessage();
         return $e->getMessage();
    }
}
*/
?>
