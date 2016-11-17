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
        <h1>Hei</h1>
        
        Otsikko:
        <input type="text" id="otsikko">
        <br>
        
        Kuvaus:
        <textarea cols="50" rows="10" id="kuvaus"></textarea>
        <br>
        
        Sijainti:
        <input type="text" id="sijainti" onkeyup="GoogleApiAutocomplete()" onblur="onAutocompleteBlur()">
        <div id="map"></div>
        <br>
        
        Alkamispäivä:
        <input type="date" id="alkamispaiva" onchange="validatedate()">
        
        
        Alkamisaika:
        <input type="time" id="alkamisaika" onchange="validatetime()">
        <br>
        
        Päättymispäivä:
        <input type="date"  id="paattymispaiva" onchange="validatedate()">
        
        Päättymisaika:
        <input type="time"  id="paattymisaika" onchange="validatetime()">
        <br>
        
        
        <p id="message">
        </p>
        <p id="message2">
        </p>
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
        <button onclick="submitEvent()">Tallenna tapahtuma</button>
        
    </body>
</html>


