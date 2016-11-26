<?php
require("../sql-connect.php");

$user = $_GET["user"];
//ei haluta hakea kaikkia käyttäjän luomia tapahtumia, joten haetaan vain sen kuukauden tapahtumat.
$timestart = urldecode($_GET["start_span"]);
$timeend = urldecode($_GET["end_span"]);


//SELECT * FROM `event` WHERE startDateTime < '2016-2-2 00:00:00'
$event = GetEventsDefTime($user, $timestart, $timeend);

header('Content-Type: application/json');

$status;
$status_message;

if(count($event) == 0){
    $status = 404;
    $status_message = "not found";
} else {
    header("HTTP/1.1 200 Success");
    $status = 200;
    $status_message = "found events";
}


$response["status"] = $status;
$response["status_message"] = $status_message;
$response["data"] = $event; 


$response["sas1"] = $timestart;
$response["sas2"] = $timeend;
$response["user"] = $user;
$response["events"] = $event;





echo json_encode($response);

?>