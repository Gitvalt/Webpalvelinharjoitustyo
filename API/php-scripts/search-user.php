<?php
require("../sql-connect.php");

$user = $_GET["user"];
$users = SearchUsers($user);

header('Content-Type: application/json');

$status;
$status_message;

if(count($users) == 0){
    $status = 404;
    $status_message = "not found";
} else {
    header("HTTP/1.1 200 Success");
    $status = 200;
    $status_message = "found users";
}


$response["status"] = $status;
$response["status_message"] = $status_message;
$response["data"] = $users; 

echo json_encode($response);

?>