<?php
require("isValidUser.php");
?>

<html lang="fi">
    <head>
        <title>Profiili</title>
        <meta charset="utf-8">

        <link href="style/profile.css" rel="stylesheet" type="text/css">
        
    </head>
    <body>
        <?php include("navbar.php"); ?>
       <?php
        
        $userdata = GetUserData($_COOKIE["user"]);
        $user = $_COOKIE["user"];
        $user_made = GetUserEvents($user);
        $shared = GetSharedEvents($user);
       
        /*
        <input type="text" name="username" readonly>
        <input type="text" name="firstname" readonly>
        <input type="text" name="lastname" readonly>
        <input type="email" name="email" readonly>
        <input type="tel" name="phonenumber" readonly>
        <input type="text" name="address" readonly>
        */
        ?>
        <table id="profile">
            <tr><td>Käyttäjätunnus</td><td><?php echo $userdata["username"]; ?></td></tr>
            <tr><td>Etunimi</td><td><?php echo $userdata["firstname"]; ?></td></tr>
            <tr><td>Sukunimi</td><td><?php echo $userdata["lastname"]; ?></td></tr>
            <tr><td>Sähköposti</td><td><?php echo $userdata["email"]; ?></td></tr>
            <tr><td>Puhelinnumero</td><td><?php echo $userdata["phone"]; ?></td></tr>
            <tr><td>Osoite</td><td><?php echo $userdata["address"]; ?></td></tr>
            <?php
            echo "<td>Tapahtumia luotu: </td><td>" . count($user_made) . "</td>";
            echo "<tr><td>Käyttäjälle jaettujen tapahtumien määrä: </td><td>" . count($shared) . "</td></tr>";
            
            ?>
        </table>
        
        <?php
        
        $log = ReadLog("*", $user);
        echo "<label for=loki>Käyttäjäloki:</label>";
        echo '<div id="loki">';
        echo '<table>';
        
        echo '<tr style="border-bottom: 1px solid black">';
        echo "<td>" . "Tapahtuman tyyppi" . "</td>";
        echo "<td>" . "Kuvaus" . "</td>";
        echo "<td>" . "Tapahtuma-aika" . "</td>";
        echo "</tr>";

        
        foreach($log as $row){
            echo "<tr>";
            echo "<td>" . $row["type"] . "</td>";
            echo "<td>" . $row["description"] . "</td>";
            echo "<td>" . $row["log_timestamp"] . "</td>";
            echo "</tr>";
            
        }
        
        echo "<table>";
        ?>
        
        <!--footer.php-->
    </body>
</html>
