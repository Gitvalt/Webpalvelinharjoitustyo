<?php
/*
Header
what happens
where happens
when happens
is it repeative event
with whom i am whit
https://developers.google.com/maps/documentation/javascript/examples/places-searchbox
*/

//mahdollisesti lisääminen luotaisiin javascriptilla

?>
<html lang="fi">
    <head>
        <title>CreateEvent</title>
        <meta charset="utf-8">
        
        <link href="style/createevent.css" type="text/css" rel="stylesheet">

        <script src="jquery/jquery-3.1.0.min.js"></script>
                <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
        <script src="CreateEvent.js"></script>
    </head>
    
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCn6UcSrDYdAiiKehVzCYy78AUQpbkFdYI&libraries=places&callback=initAutocomplete"
         async defer>
    </script>

    <body>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="event.preventDefault(); eventSubmit();" method="post" id="form">
            <h1>Tapahtuman luominen</h1>

            Otsikko:
            <input type="text" id="otsikko" name="otsikko">
            <br>

            <label for="kuvaus">Kuvaus:</label>
            <textarea cols="50" rows="10" id="kuvaus" name="kuvaus"></textarea>
            <br>

            <div id="sijainti_style">
                Sijainti:
                <input type="text" id="sijainti" name="sijainti" onkeyup="GoogleApiAutocomplete(this, event)" onblur="onAutocompleteBlur()">
                <div id="map"></div>
            </div>
            <br>

            <div id="time">
                <div id="alkaa">
                    <label for="alkaa">Tapahtuma alkaa</label>
                    <input type="date" id="alkamispaiva" name="alkudate" onchange="validatedate()"><br>
                    <input type="time" id="alkamisaika" name="alkutime" onchange="validatetime()">
                </div>


                <div id="loppuu">
                    <label for="loppuu">Tapahtuma päättyy:</label>
                    <input type="date"  id="paattymispaiva" name="loppudate" onchange="validatedate()"><br>
                    <input type="time"  id="paattymisaika" name="lopputime" onchange="validatetime()">
                </div>
                <br>
            </div>

            <p id="message"></p>
            <p id="message2"></p>
            <br>

            <i>Under construction:</i><br>

            Tapahtuman osallistujat:
            <div id="osallistujat">
                <input type="text" id="osallistujafield"  onkeyup="GetUsers(this, event)">
                <div id="users"></div>
                <br>
            </div>

            <div id="valituthenkilot">
                <div id="sel_users"></div>
                <br>
            </div>
            <input type="submit" name="submit">
            <!--<button onclick="submitEvent()">Tallenna tapahtuma</button>-->
        </form>
    </body>
</html>



