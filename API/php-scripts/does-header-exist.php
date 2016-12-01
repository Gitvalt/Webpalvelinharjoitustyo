<?php
require("../sql-connect.php");

$user = $_GET["user"];
$header = $_GET["header"];

$exist = DoesHeaderExist($header, $user);

header('Content-Type: application/json');

$status;
$status_message;

if($exist === false){
    $status = 404;
    $status_message = "not found";
} else {
    header("HTTP/1.1 200 Success");
    $status = 200;
    $status_message = "found header";
}


$response["status"] = $status;
$response["status_message"] = $status_message;
$response["data"] = $exist; 

echo json_encode($response);

?>