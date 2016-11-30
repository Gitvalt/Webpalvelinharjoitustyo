<?php
require_once("../API/sql-connect.php");
$token = $_COOKIE["token"];

if(empty($token)){
    
    unset($_COOKIE["token"]);
    unset($_COOKIE["user"]);
    
    header("Location: ../login.php");
    
} else {
    if(isValidToken($token) == true){
        
    } else {
        
        unset($_COOKIE["token"]);
        unset($_COOKIE["user"]);

        header("Location: ../login.php");

    }   
}
                    
?>