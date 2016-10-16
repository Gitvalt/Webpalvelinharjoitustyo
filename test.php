<html lang="en">
    <head>
        <title>Calendarprinting</title>
        <meta charset="utf-8">
        <meta description="This is calender printing test">
        <link href="style/style.css" rel="stylesheet" type="text/css">
        <script src="javascript/calendarprint.js"></script>
    </head>
    <body>
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
            POST-PHP CalenderPrint<br>
            <input type="date" name="selected_date" value="">
            <hr>

            Javascript CalendarPrint<br>
            <div id="nav">
            <input type="button" value="JavaScript" onclick="JavaPrint()">

            <input type="text" id="day" readonly>
            <input type="text" id="month" readonly>
            <input type="text" id="year" readonly>

            </div>
        </form>
        <div id="output">

        </div>
    </body>
</html>

<?php

include_once("PHPscript.php");

?>
