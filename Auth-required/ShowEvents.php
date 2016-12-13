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
        echo '<label for="tapahtumalista">Päättymispäivä punaisella taustalla = päättynyt tapahtuma, </label>';
        //echo "<h1>Käyttäjä: $user</h1>";
        echo "<h2>Tapahtumat:</h2>";
        echo '<table id="tapahtumalista">';
        
         echo "<tr>";
                    echo "<td>" . "Otsikko" . "</td>";
                    echo "<td>" . "Kuvaus" . "</td>";
                    echo "<td>" . "Tapahtuma alkaa" . "</td>";
                    echo "<td>" . "Tapahtuma päättyy" . "</td>";
                    echo "<td>" . "Tapahtuman sijainti" . "</td>";
                    echo "<td>" . "Omistaja" . "</td>";
            
        
        echo "</tr>";
        echo "<pre>";
        //print_r($events);
        echo "</pre>";
        if(!empty($events)){
        foreach($events as $eventtype){
            //0 made by user
            //1 shared to user
            
                $style = 'class="sharedevent"';
                $style_2 = 'class="sharedevent2"';
            
                $index = 0;
                foreach($eventtype as $event){
                    if($index == 0){
                            $now = date("Y-m-d");
                        
                        if($event["owner"] == $_COOKIE["user"]){
                            echo "<tr " . $style . " >";    
                        } else {
                             echo "<tr " . $style_2 . " >";    
                        }
                            echo "<td>" . "<a href=\"EditEvent.php?header=" . $event["header"] . "&id=" , $event["id"] .  "\">" . $event["header"] . "</a></td>";
                            echo "<td>" . $event["description"] . "</td>";
                            echo "<td>" . $event["startDateTime"] . "</td>";

                            if($event["endDateTime"] < $now){
                                echo "<td style='background: red;'>" . $event["endDateTime"] . "</td>";
                            } else {
                                echo "<td>" . $event["endDateTime"] . "</td>";
                            }
                            echo "<td>" . $event["location"] . "</td>";
                            echo "<td>" . $event["owner"] . "</td>";
                        
                        echo "</tr>";

                            $index++;
                    } else {
                        
                        if($event["owner"] == $_COOKIE["user"]){
                            echo "<tr " . $style . " >";    
                        } else {
                             echo "<tr " . $style_2 . " >";    
                        }
                    
                            echo "<td>" . "<a href=\"EditEvent.php?header=" . $event["header"] . "\">" . $event["header"] . "</a></td>";
                            echo "<td>" . $event["description"] . "</td>";
                            echo "<td>" . $event["startDateTime"] . "</td>";
                            if($event["endDateTime"] < $now){
                                echo "<td style='background: red;'>" . $event["endDateTime"] . "</td>";
                            } else {
                                echo "<td>" . $event["endDateTime"] . "</td>";
                            }
                            echo "<td>" . $event["location"] . "</td>";
                            echo "<td>" . $event["owner"] . "</td>";
                        
                        echo "</tr>";

                            $index = 0;
                    }
                }
            } 
        } else {
                echo "<tr>";
                        echo "<td colspan=6>" . "No events found" . "</td>";
                echo "</tr>";
            }
        
        echo "</table>";
        
        
        ?>
        <!--footer.php-->
    </body>
</html>
