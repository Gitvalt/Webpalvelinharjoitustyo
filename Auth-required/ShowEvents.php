<?php
require("isValidUser.php");
?>

<html lang="fi">
    <head>
        <title>Kirjaudu Sisään</title>
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
        
        echo "<h1>Käyttäjä: $user</h1>";
        
        echo "<h2>Tapahtumat:</h2>";
        echo "<table>";
        
         echo "<tr>";
                    echo "<td>" . "Otsikko" . "</td>";
                    echo "<td>" . "Kuvaus" . "</td>";
                    echo "<td>" . "Tapahtuma alkaa" . "</td>";
                    echo "<td>" . "Tapahtuma päättyy" . "</td>";
                    echo "<td>" . "Tapahtuman sijainti" . "</td>";
                echo "</tr>";
        
        foreach($events as $eventtype){
            //0 made by user
            //1 shared to user
            
            foreach($eventtype as $event){
                echo "<tr>";
                    echo "<td>" . $event["header"] . "</td>";
                    echo "<td>" . $event["description"] . "</td>";
                    echo "<td>" . $event["startDateTime"] . "</td>";
                    echo "<td>" . $event["endDateTime"] . "</td>";
                    echo "<td>" . $event["location"] . "</td>";
                echo "</tr>";
                
            }
            
        }
        
        echo "</table>";
        
        
        ?>
        <!--footer.php-->
    </body>
</html>