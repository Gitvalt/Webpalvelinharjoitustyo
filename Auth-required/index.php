<?php
require("isValidUser.php");
?>

<html lang="fi">
    <head>
        <title>Kirjaudu Sisään</title>
        <meta charset="utf-8">
    </head>
    <body>
        <?php include("navbar.php"); ?>
        Kirjauduit onnistuneesti sisään palveluun.<br>
        
        <?php
        
        echo $_COOKIE["user"] . "<br>";
        echo $_COOKIE["token"] . "<br>";
        ?>
        <!--footer.php-->
    </body>
</html>
