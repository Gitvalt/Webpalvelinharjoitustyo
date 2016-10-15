<?php
function Connect(){
    try {
        $host = "localhost";
        $database = "testdatabase";
        $user = "root";
        $password = "";

        $conn = new PDO("mysql:host=$host;dbname=$database", $user, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
        }
    catch(PDOExecption $ex){
        echo $ex->getMessage();
    }
}

function GetTableData($table){
     try {
        $conn = Connect();
        $statement = $conn->prepare("SELECT * FROM ?;");
        $statement->bindValue(1, $table, PDO::PARAM_STR);
        $statement->execute();
        $tulos = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $tulos;

    } catch(PDOException $e){
        echo "error:" . $e->getCode();
         print_r($e["file"]);

    }
}
?>
