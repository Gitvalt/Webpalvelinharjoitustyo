<?php
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

//Return all event id:s that have been shared with user x
//eventID = reference to event field -> id
function GetSharedEventIDs($user){
     
    try {
        $conn = Connect();
        
        $statement = $conn->prepare("Select eventID from sharedevent where username=?;");
        
        $statement->bindValue(1, $user, PDO::PARAM_STR);
        
        $statement->execute();
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $results;

    } catch(PDOException $e){
        return null;
    }
}

function GetSharedEvents($user){
     
    try {
        
        $sharedEvents = GetSharedEventIDs($user);
        
    
        if($sharedEvents != null){
            
            $conn = Connect();
            $results = array();
            
            foreach($sharedEvents[0] as $id){
                
                $statement = $conn->prepare("Select * from event where id=?;");
                
                $statement->bindValue(1, $id, PDO::PARAM_STR);
                $statement->execute();
                $var = $statement->fetch(PDO::FETCH_ASSOC);
                array_push($results, $var);
                
            }
           
            return $results;
    
        } else {
            return null;
        }
        
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
        
        return true;

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


?>
