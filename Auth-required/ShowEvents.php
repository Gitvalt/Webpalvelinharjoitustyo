<?php
require("isValidUser.php");
?>

<html lang="fi">
    <head>
        <title>Tapahtumien selailu</title>
        <meta charset="utf-8">
        <link href="style/ShowEvents.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <?php include("navbar.php"); ?>
        <?php
        $user = $_COOKIE["user"];
        $events = UserAllEvents($user);
        //echo "<pre>";
        //print_r($events);
        
        //echo "<h1>Käyttäjä: $user</h1>";
        echo "<h2>Tapahtumat:</h2>";
        echo "<table>";
        
         echo "<tr>";
                    echo "<td>" . "Otsikko" . "</td>";
                    echo "<td>" . "Kuvaus" . "</td>";
                    echo "<td>" . "Tapahtuma alkaa" . "</td>";
                    echo "<td>" . "Tapahtuma päättyy" . "</td>";
                    echo "<td>" . "Tapahtuman sijainti" . "</td>";
                echo "</tr>";
        if(!empty($events)){
        foreach($events as $eventtype){
            //0 made by user
            //1 shared to user
            
                $index = 0;
                foreach($eventtype as $event){
                    if($index == 0){
                        $now = date("Y-m-d");
                        
                    echo "<tr>";
                        echo "<td>" . "<a href=\"EditEvent.php?header=" . $event["header"] . "\">" . $event["header"] . "</a></td>";
                        echo "<td>" . $event["description"] . "</td>";
                        echo "<td>" . $event["startDateTime"] . "</td>";
                        
                        if($event["endDateTime"] < $now){
                            echo "<td style='background: red;'>" . $event["endDateTime"] . "</td>";
                        } else {
                            echo "<td>" . $event["endDateTime"] . "</td>";
                        }
                        echo "<td>" . $event["location"] . "</td>";
                    echo "</tr>";
                        
                        $index++;
                    } else {
                    echo "<tr style='background: lightgray;'>";
                        echo "<td>" . "<a href=\"EditEvent.php?header=" . $event["header"] . "\">" . $event["header"] . "</a></td>";
                        echo "<td>" . $event["description"] . "</td>";
                        echo "<td>" . $event["startDateTime"] . "</td>";
                        if($event["endDateTime"] < $now){
                            echo "<td style='background: red;'>" . $event["endDateTime"] . "</td>";
                        } else {
                            echo "<td>" . $event["endDateTime"] . "</td>";
                        }
                        echo "<td>" . $event["location"] . "</td>";
                    echo "</tr>";
                        
                        $index = 0;
                    }
                }
            } 
        } else {
                echo "<tr>";
                        echo "<td colspan=5>" . "No events found" . "</td>";
                echo "</tr>";
            }
        
        echo "</table>";
        
        
        ?>
        <!--footer.php-->
    </body>
</html>
