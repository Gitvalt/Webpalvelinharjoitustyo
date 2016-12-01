<?php
require("isValidUser.php");

?>

<html lang="fi">
    <head>
        <title>Tervetulos kalenteripalveluun</title>
        <meta charset="utf-8">

        <script src="../jquery/jquery-3.1.0.min.js"></script>
        <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
        <script src="javascript/index_page.js"></script>
        
    </head>
    <body>
        <?php include("navbar.php"); ?>
        <?php echo $_COOKIE["user"]; ?>! Kirjauduit onnistuneesti palveluun.<br>
        
        <div id="server_info" style="margin-top: 20px;">
        </div>
        
        <?php
            //GetEventsDefTime($user, $start_inp, $end_inp)
        ?>
        
        
        
        <hr>
        <a href="php-syntax-highlighter.php">php-syntax-highlighter</a>
        
        <!--footer.php-->
    </body>
</html>
