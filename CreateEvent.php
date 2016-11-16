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
    
    <script>
            try{
            var user = document.getElementById("osallistujafield").value;
        
            var users2 = document.getElementById("users2");
            
            while(users2.childElementCount > 0){
                users2.removeChild(users2.firstChild);
            }
            
            } catch(Exception){
                
            }
            
            if(user == null){
                console.log("no user input");
            } else {
        
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
                            /*
                            var li = document.createElement("li");
                            var a = document.createElement("a");

                            a.onclick = AddUserToList(data.data[x].username);
                            a.textContent = data.data[x].username;

                            console.log(data.data[x].username);

                            li.appendChild(a);

                            //document.getElementById("osallistujafield").appendChild(li);
                            */
                            array.push(data.data[x].username);
                            
                        }
                        console.log(array);
                        $( "#osallistujafield" ).autocomplete({
                            source: array
                            });
                        
                    } else {
                        console.log("Error or no data found");
                    }

                    });
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
        
        <script>
        //autocomplete
        </script>
        
        
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


