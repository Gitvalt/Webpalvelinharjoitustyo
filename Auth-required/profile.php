<?php
require("isValidUser.php");
?>

<html lang="fi">
    <head>
        <title>Profiili</title>
        <meta charset="utf-8">
    </head>
    <body>
        <?php include("navbar.php"); ?>
        Profiilisivu: <?php echo $_COOKIE["user"]; ?> 
       
        <!--footer.php-->
    </body>
</html>
