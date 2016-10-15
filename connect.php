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

function GetUserData($user){
     try {
        $conn = Connect();
        $user = htmlspecialchars($user);
        $statement = $conn->prepare("SELECT * FROM person where username=?;");
        $statement->bindValue(1, $user, PDO::PARAM_STR);
        $statement->execute();
        $tulos = $statement->fetch(PDO::FETCH_ASSOC);;
        return $tulos;

    } catch(PDOException $e){
        //echo "error:" . $e->getMessage();
    }
}


?>
