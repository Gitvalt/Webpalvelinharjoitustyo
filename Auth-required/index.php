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
        <?php echo $_COOKIE["user"]; ?>! Kirjauduit onnistuneesti palveluun.<br>
        <hr>
        <a href="php-syntax-highlighter.php">php-syntax-highlighter</a>
        
        <!--footer.php-->
    </body>
</html>
