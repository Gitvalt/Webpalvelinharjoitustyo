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
    
        <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 30%;
        width: 40%;
      }
      
    </style>
    
    </head>
    
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCn6UcSrDYdAiiKehVzCYy78AUQpbkFdYI&libraries=places&callback=initAutocomplete"
         async defer>
    </script>
    
    
    
    <script>
    
        function AddUserToList(){
            var field = document.getElementById("osallistujafield");
            var input = field.value;
            var li = document.createElement("li");
            if(input != null){
                li.textContent = input;
                document.getElementById("divusers").appendChild(li);
                field.value = "";
                field.focus();
            }
        }
        
        function validate(){
            /*
            var x = document.getElementById("alkamisaika");
            
            var y = document.getElementById("paattymisaika");
            
            if(x.value > y.value){
              var text = "alkaa ennen loppumista";
            document.getElementById("message").value = text;
            } else {
            var text = "OK";
            document.getElementById("message").value = text;            
            }
            */
        }
        
        function initAutocomplete() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: -33.8688, lng: 151.2195},
          zoom: 13,
          mapTypeId: 'roadmap'
        });
        }
        
        
        function GoogleApiAutocomplete(){
          
          var input = document.getElementById("sijainti");
            
            var option = { type: ["address"]
                         };
        
            
            var autocomplete = new google.maps.places.Autocomplete(input, option);

        
        }
        
         // This example requires the Places library. Include the libraries=places
      // parameter when you first load the API. For example:
      // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">
    
        
    
        
    </script>
    
    
    
    <body>
        <h1>Hei</h1>
        
        Otsikko:
        <input type="text" name="otsikko">
        <br>
        
        Kuvaus:
        <textarea cols="50" rows="10" id="kuvaus"></textarea>
        <br>
        
        Sijainti:
        <input type="text" id="sijainti" onchange="GoogleApiAutocomplete()">
        <div id="map"></div>
        <br>
        
        Alkamisaika:
        <input type="date" id="alkamisaika" onchange="validate()">
        <br>
        
        Päättymisaika:
        <input type="date"  id="paattymisaika" onchange="validate()">
        <br>
        
        <p id="message">
        </p>
        <br>
        Tapahtuman toistuvuus:
        <select>
            <option>1</option>
            <option>2</option>
        </select>
        <br>
        
        Tapahtuman osallistujat:
        <input type="text" id="osallistujafield" onchange="x">
        <button onclick="AddUserToList()">Add</button>
        <div id="divusers">
            <ul id="users">
            </ul>
        </div>
        <br>
        
    </body>
</html>


