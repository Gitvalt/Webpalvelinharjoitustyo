<?php
require("../sql-connect.php");

$user = $_GET["user"];
$users = SearchUsers($user);

if(count($users) == 0){
    echo false;
} else {
    echo json_encode($users);
}
?>