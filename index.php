<html lang="fi">
    <head>
        <title>Etusivu</title>
        <meta charset="utf-8">
        <link href="style/style.css">
    </head>
    <body>
        <?php //include("navbar.php"); ?>
        <h1>Tervetuloa kalenteri sovellukseen!</h1>
        <a href="Auth-required/index.php">Kirjaudu sisään</a>
        <a href="php-syntax-highlighter.php">php-syntax-highlighter</a><br>
        
        <!--footer.php-->
    </body>
</html>

<?php
echo "<hr>";
echo "<div>";
$handler = fopen("config/config.txt", "r");

$content = fread($handler, filesize("config/config.txt"));
$contents = explode("\n", $content);

foreach($contents as $line){
    $words = explode(":", $line);
    echo $words[1] . "<br>";
}
echo "</div>";
?>