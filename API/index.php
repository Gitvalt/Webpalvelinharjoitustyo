<?php
$array = array(2,3,4,5);

//200 = ok, 400 = no
/*
$uri = explode("API/",$_SERVER["REQUEST_URI"]);
echo json_encode($uri);
*/

if(empty($_GET["index"])){
    Response(400,"Response fail", null);
} else {
    Response(200, "Response ok", $array[$_GET["index"]]);
}


function Response($status, $status_message, $data){
    header('Content-Type: application/json');
    header("HTTP/1.1 $status $status_message");
    
    $response["status"] = $status;
    $response["status_message"] = $status_message;
    $response["data"] = $data; 
        
    echo json_encode($response);
}



?>