    
    function AddUserToList(x){
            console.log("addtouserlist");
            var field = document.getElementById("osallistujafield");
            
            var sel_users = document.getElementById("sel_users");
            
            var user = x.textContent;
            
            var li = document.createElement("li");
            li.textContent = user;
            li.setAttribute("name", "selected_user");
        
        
            var selected = document.getElementsByName("selected_user");
        
            console.log(selected);
            
            var error = false;
        
            for(var x = 0; x<selected.length;x++){
                
                if(selected[x].textContent == user){
                    console.log("already selected");
                    error = true;
                    break;
                }
                
            }
            //document.cookie += "user=testi";

        
            if(error === false){
                //console.log(document.cookie);
                sel_users.appendChild(li);
            }
        }
        

        function emptyUsers(){
            var ul = document.getElementById("users");

                    if(ul.childElementCount != 0){
                        while(ul.childElementCount > 0){
                            ul.removeChild(ul.firstChild);
                        }
                    }
        }
    
    
        function GetUsers(parent, event){
            
            try{
                
                var user = document.getElementById("osallistujafield").value;

                var users2 = document.getElementById("users");

                while(users2.childElementCount > 0){
                    users2.removeChild(users2.firstChild);
                }

            } catch(Exception){
                
            }
            
            if(user == null){
                console.log("no user input");
            } else {
        
            $.ajax({
                url: './API/users/search/' + user, method: "GET"
                 }).fail(function (data) {
                        console.log("fail!");
                        console.log(data.responseText);
                        
                }).done(function (data) {

                    //var x = data.kentta["geometry"]["location"];
                   
                    var array = [];
                    console.log(data);
                    if(data.status === 200){
                        console.log("Data found");
                        
                        for(var x = 0; x<data.data.length;x++){
                            
                            var li = document.createElement("li");
                            var a = document.createElement("a");
                            
                        
                            
                            a.onclick = function(){
                                console.log("click");
                                AddUserToList(this);
                            };
                            a.href = "#";
                            a.textContent = data.data[x].username;
                            a.tagName = "option";
                            
                            console.log(data.data[x].username);

                            li.appendChild(a);
                            
                            var ul = document.getElementById("users");
                            
                            if(ul.childElementCount != 0){
                                while(ul.childElementCount > 0){
                                    ul.removeChild(ul.firstChild);
                                }
                            }
                            
                            document.getElementById("users").appendChild(li);
                            
                            array.push(data.data[x].username);
                            
                        }
                        
                     
                        
                    } else {
                        console.log("Error or no data found");
                    }

                    });
                }
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
          center: {lat: 61.92410999999999, lng: 25.748151000000007},
          zoom: 5,
          mapTypeId: 'roadmap'
        });
        
        }
        
        
        function GoogleApiAutocomplete(parent, event){
            
            var keyCode = event.keyCode;
            
            var input = document.getElementById("sijainti");
            
            var option = { type: ["address"] };
        
            var autocomplete = new google.maps.places.Autocomplete(input, option);
            
            switch(keyCode){
                case 13:
                    console.log("Enter");
                    refreshmap();
                    break;
            }
            
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
         

        function getEventId(eventheader){
    
        var user = document.cookie;
        console.log(user);
    
            $.ajax({
        url: './API/users/' + user + '/events/' + eventheader + "apikey=notimplemented", method: "GET"
         }).fail(function () {
                console.log("Creating event failed!");
        }).done(function (data) {
               console.log(data); 
            }
        );
        
        }


    
        function eventSubmit(){
        
        
            
        var otsikko = document.getElementById("otsikko").value;
        
        var kuvaus = document.getElementById("kuvaus").value;
        
        var sijainti = document.getElementById("sijainti").value;
        
        var alkamispaiva = document.getElementById("alkamispaiva").value;
        
        var alkamisaika = document.getElementById("alkamisaika").value;
        
        var loppumispaiva = document.getElementById("paattymispaiva").value;
        
        var loppumisaika = document.getElementById("paattymisaika").value;
        
        if(alkamisaika != "" || alkamispaiva != ""){
            var alkamisajankohta = alkamispaiva + " " + alkamisaika + ":00";    
        } else {
            var alkamisajankohta = "";
        
        }
            
        if(loppumisaika != "" || loppumispaiva != ""){
            var loppumisajankohta = loppumispaiva + " " + loppumisaika + ":00";    
        } else {
            var loppumisajankohta = "";
        
        }
        
        //console.log(alkamisajankohta);
        
        //otsikko cant contain space
       
        var shareTo = document.getElementsByName("selected_user");    
            
        var error = false;
            
            
        if(otsikko == ""){
            console.log("otsikko ei ole määriteltynä");
            document.getElementById("otsikko").style.border = "1px solid red";
            error = true;
        }
            
            
            
        
            
            
        if(alkamispaiva == "" && loppumispaiva == ""){
            console.log("Päivämäärä ei ole määritelty.");
        } else {
            
            if(alkamispaiva > loppumispaiva){
                console.log("tapahtuma alkaisi ennen sen loppumista");
                document.getElementById("alkamispaiva").style.border = "1px solid red";
                error = true;
            } 
            
            if(alkamispaiva < loppumispaiva){
                 console.log("päivä ok");
                 document.getElementById("alkamispaiva").style.border = "";
                 document.getElementById("paattymispaiva").style.border = "";
                 error = true;
            } 
            
            
            if(alkamispaiva == loppumispaiva){
                    
                    console.log(alkamispaiva + ";" + loppumispaiva);
                
                    if(alkamisaika >= loppumisaika){
                        console.log("tapahtuma alkaisi ennen sen loppumista");
                        document.getElementById("alkamisaika").style.border = "1px solid red";
                        document.getElementById("paattymisaika").style.border = "1px solid red";
                        error = true;
                    } else {
                        document.getElementById("alkamisaika").style.border = "";
                        document.getElementById("paattymisaika").style.border = "";
                    }
            }  
        }
            
 
            if(error === false){
                
                var user = "testi";
                $.ajax({
                url: './API/users/' + user + "/events/" + otsikko + "/apikey=nice", method: "POST", data: { description: kuvaus, startdatetime: alkamisajankohta, enddatetime: loppumisajankohta, location: sijainti }
                 }).fail(function (data) {
                        console.log("fail!");
                        console.log(data.responseText);
                }).done(function (data) {
                    console.log(data);
                    shareTarget(shareTo, data.data.id);
                });
                
        
                
                
                console.log("Tapatuma luotiin");
                
                
            } else {
                console.log("Tapahtumaa ei luotu.")
            }
    
    }


    function DoesHeaderExist(){
        
    }

    function shareTarget(toUsers, tapahtumaID){
        
        var user = "testi";  
        var eventid = tapahtumaID;                                               
        
        console.log("id: " + eventid);
        console.log(toUsers);
        console.log(toUsers[0].textContent);
        
        for(var x = 0; x<toUsers.length;x++){
            
            
            
            
            /*
            ^users/([0-9a-zA-Z]+)/events/([0-9a-zA-Z]+)/share/([0-9a-zA-Z]+)/apikey=([0-9a-zA-Z]+)*$   index.php?type=Share&selecteduser=$3&eventid=$2&owner=$1&apikey=$4 [NC,L]
            */
            
            
            $.ajax({
                url: './API/users/' + user + "/events/" + tapahtumaID + "/share/" + toUsers[x].textContent + "/apikey=nice", method: "POST"
                 }).fail(function (data) {
                        console.log("fail!");
                        console.log(data.responseText);
                }).done(function (data) {
                    console.log(data);
                });
                
        }
        
    }
