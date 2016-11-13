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
      
            #divusers2 li {
                background: red;
                text-decoration: none;
                list-style: none;
                
            }
            
    </style>
    <script src="jquery/jquery-3.1.0.min.js"></script>
    </head>
    
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCn6UcSrDYdAiiKehVzCYy78AUQpbkFdYI&libraries=places&callback=initAutocomplete"
         async defer>
    </script>
    
    
    
    <script>
    
        function AddUserToList(x){
            
            var field = document.getElementById("osallistujafield");
            var input = field.value;
            
            var ul = document.getElementById("divusers");
            while(ul.childElementCount > 0){
                ul.removeChild(ul.firstChild);
            }
            
            var li = document.createElement("li");
            if(input != null){
                li.textContent = input;
                document.getElementById("divusers").appendChild(li);
                field.value = "";
                field.focus();
            }
            
        }
        
        
        
        
        function GetUsers(){
            
            var user = document.getElementById("osallistujafield").value;

            var users2 = document.getElementById("users2");
            
            while(users2.childElementCount > 0){
                users2.removeChild(users2.firstChild);
            }
            
            $.ajax({
                url: 'http://localhost:8080/html/web-palvelinohjelmointi/Webpalvelinharjoitustyo/API/users/search/' + user, method: "GET"
                 }).fail(function () {
                        console.log("fail!");
                }).done(function (data) {

                    //var x = data.kentta["geometry"]["location"];
                    console.log(data.status);
                    console.log(data.data.length);
                
                    var array = [];
                
                    if(data.status === 200){
                        console.log("Data found");
                        
                        for(var x = 0; x<data.data.length;x++){
                            
                            var li = document.createElement("li");
                            var a = document.createElement("a");

                            a.onclick = AddUserToList(data.data[x].username);
                            a.textContent = data.data[x].username;

                            console.log(data.data[x].username);

                            li.appendChild(a);

                            //document.getElementById("osallistujafield").appendChild(li);
                            
                            array.push(data.data[x].username);
                            
                        }
                        
                        
                    $( "#osallistujafield" ).autocomplete({
                                source: array
                            });
                         
                        

                    } else {
                        console.log("Error or no data found");
                    }

                    });
            
        }
        
        function validatetime(){
            
            var x = document.getElementById("alkamisaika");
            
            var y = document.getElementById("paattymisaika");
            
            if(x.value > y.value){
              var text = "alkaa ennen loppumista";
            document.getElementById("message").textContent = text;
            } else {
            var text = "OK";
            document.getElementById("message").textContent = text;            
            }
            
        }
        
        function validatedate(){
            
            var x = document.getElementById("alkamispaiva");
            
            var y = document.getElementById("paattymispaiva");
            
            if(x.value > y.value){
              var text = "alkaa ennen loppumista";
            document.getElementById("message2").textContent = text;
            } else {
            var text = "OK";
            document.getElementById("message2").textContent = text;            
            }
            
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
            
            var option = { type: ["address"] };
        
            var autocomplete = new google.maps.places.Autocomplete(input, option);
        }
        
        function onAutocompleteBlur(){
            console.log("onBlur");
             
            var input = document.getElementById("sijainti");
            
            refreshmap();
            
        }
        
        
        function refreshmap(){
            
        var location = document.getElementById("sijainti").value;
            
         $.ajax({
        url: 'https://maps.googleapis.com/maps/api/geocode/json', data : {sensor:false, address : location }
         }).fail(function () {
        console.log("fail!");
        }).done(function (data) {
        
            //var x = data.kentta["geometry"]["location"];
            //console.log(data.results[0]);
        
            //ajax tulos
            var result = data.results[0];
            var result_location = result.geometry.location;
            
            if(result){
            
            var map = new google.maps.Map(document.getElementById('map'));
            map.setZoom(5);
            map.setCenter(new google.maps.LatLng(result_location.lat, result_location.lng)); 
            
             var marker = new google.maps.Marker({
                position: new google.maps.LatLng(result_location.lat, result_location.lng)
                , map: map
                , // include data to marker -> show in infowindow
                title: result.formatted_address
            });
                
                }
            });
        }
         // This example requires the Places library. Include the libraries=places
      // parameter when you first load the API. For example:
      // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">
    
    function submitEvent(){
        
        var otsikko = document.getElementById("otsikko").value;
        
        var kuvaus = document.getElementById("kuvaus").value;
        
        var sijainti = document.getElementById("sijainti").value;
        
        var alkamispaiva = document.getElementById("alkamispaiva").value;
        
        var alkamisaika = document.getElementById("alkamisaika").value;
        
        var loppumispaiva = document.getElementById("paattymispaiva").value;
        
        var loppumisaika = document.getElementById("paattymisaika").value;
        
        var alkamisajankohta = alkamispaiva + " " + alkamisaika + ":00";
        var loppumisajankohta = loppumispaiva + " " + loppumisaika + ":00";
        
        //console.log(alkamisajankohta);
        
        //otsikko cant contain space
       
        var otsikko_reg = /([0-9a-zA-Z]+)/;
        if(otsikko_reg.exec(otsikko) == true){
            
        
        $.ajax({
        url: './API/users/testi/events/' + otsikko + "apikey=notimplemented", method: "POST", data: {description: kuvaus, startdatetime: alkamisajankohta, enddatetime: loppumisajankohta, location: sijainti}
         }).fail(function () {
        console.log("Creating event failed!");
        }).done(function (data) {
                
                console.log("done");    
            
            }
        );
        }
    
    }
    
        
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
        <input type="text" id="sijainti" onchange="GoogleApiAutocomplete()" onblur="onAutocompleteBlur()">
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
        <input type="text" id="osallistujafield" onchange="GetUsers()">
        
        <div id="divusers2">
            <h2>Options</h2>
            <ul id="users2">
            </ul>
        </div>
        
        
        <div id="divusers">
            <h2>Selected users</h2>
            <ul id="users">
            </ul>
        </div>
        <br>
        
        <button onclick="submitEvent()">Tallenna tapahtuma</button>
        
    </body>
</html>


