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

//Get every event from user. Does not include shared events.
function GetUserEvents($user){
     try {
        $conn = Connect();
        $user = htmlspecialchars($user);
         
        $statement = $conn->prepare("SELECT * FROM event where owner=?;");
        
        $statement->bindValue(1, $user, PDO::PARAM_STR);
        $statement->execute();
        
        $tulos = $statement->fetchAll(PDO::FETCH_ASSOC);;
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
    }
}

function GetUserData($user){
     try {
        $conn = Connect();
        $user = htmlspecialchars($user);
        $statement = $conn->prepare("SELECT username, firstname, lastname, phone, email, address FROM user where username=?;");
        $statement->bindValue(1, $user, PDO::PARAM_STR);
        $statement->execute();
        $tulos = $statement->fetch(PDO::FETCH_ASSOC);;
        return $tulos;

    } catch(PDOException $e){
        //echo "error:" . $e->getMessage();
    }
}


?>
