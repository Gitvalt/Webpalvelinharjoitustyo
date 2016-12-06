    /*
    Functions for CreateEvent and EditEvent pages
    */
    
    function DeleteEvent(user, id, token){
          $.ajax({
            url: '../API/index.php?type=userEvent&index=' + id + '"&user=' + user + '&apikey=' + token, method: "DELETE"
                 }).fail(function (data) {
                        console.log("fail!");
                        console.log(data.responseText);
                        
                }).done(function (data) {
                    console.log("Data found");
                    console.log(data);
                    
                    if(data.data == true){
                        window.alert("Tapahtuma poistettiin!");
                        window.location.replace("ShowEvents.php");
                    } else {
                        window.alert("Tapahtuman poistaminen ei onnistunut!");
                    }
                    
                 });
            
    }

    function HandleRemove(event){
        if(confirm("oletko varma!") == true){
            console.log("true");
            //index.php?type=userEvent&index=$2&user=$1&apikey=$3
            
            
            var user = "";
            var token = "";
            
            
            var header = document.getElementById("otsikko");
            var cookies = GetCookies();
            console.log("cookies");
            console.log(cookies);
            
            for(var x = 0; x < cookies.length; x++){
                
                var help2 = cookies[x].split("=");
                //console.log(help2);
                var helper = help2[0] .replace(" ", "");
                
                if(helper == "user"){
                    user = help2[1];
                }
                
                if(helper == "token"){
                    token = help2[1];
                }
                    
            }
            
            //index.php?type=eventID&user=$1&eventheader=$2&apikey=$3
            $.ajax({
            url: '../API/index.php?type=eventID&user=' + user + '&eventheader=' + header.value + '&apikey=' + token, method: "GET"
                 }).fail(function (data) {
                        console.log("fail!");
                        console.log(data.responseText);
                        
                }).done(function (data) {
                    console.log("Data found");
                    console.log(data);
                    DeleteEvent(user, data.data.id, token);
                 });
            
            console.log(user);
            console.log(token);
            
        } else {
            console.log("Poistaminen keskeytettiin!");
        }    
    }

    function doesHeaderExist(){
            
            var user = "";
            console.log("user:" + user);
        
            var header = document.getElementById("otsikko");
            var cookies = GetCookies();
            console.log("cookies");
            console.log(cookies);
        
            for(var x = 0; x < cookies.length; x++){
                
                var help2 = cookies[x].split("=");
                //console.log(help2);
                var helper = help2[0].replace(" ", "");
                if(helper == "user"){
                    user = help2[1];
                }
            }
            
        
        
            $.ajax({
                url: '../API/php-scripts/does-header-exist.php?user=' + user + '&header=' + header.value, method: "GET"
                 }).fail(function (data) {
                        console.log("fail!");
                        console.log(data.responseText);
                        
                }).done(function (data) {
                    console.log("Data found");
                    console.log(data);
                
                    //original piiloitettu input, joka sisältää alkuperäisen otsikon tapahtumien muokkauksen yhteydessä.
                
                    var original = document.getElementById("original");
                    if(original != null){
                        if(data.status === 200 && original.value != header){
                                header.style.border = "1px solid red";
                                document.getElementById("error_otsikko").textContent = "Otsikko on jo olemassa";
                                return true;
                            } else {
                                header.style.border = "";
                                document.getElementById("error_otsikko").textContent = "";
                                return false;
                            }
                    } else {
                        if(data.status === 200){
                                header.style.border = "1px solid red";
                                document.getElementById("error_otsikko").textContent = "Otsikko on jo olemassa";
                                return true;
                            } else {
                                header.style.border = "";
                                document.getElementById("error_otsikko").textContent = "";
                                return false;
                            }
                    }
            });
    }

    function RemoveUser(value){
        console.log("RemoveUser");
        console.log(value);
    
        var targetUser = value.srcElement.innerHTML;
        
        var selected = document.getElementsByName("selected_user");
        
        /*
        console.log(selected);
        console.log(selected.children);
        console.log("<hr>");
        console.log(selected[0]);
        console.log(selected[0].children);
        */
        
        //käydään läpi lapsielementit ja poistetaan listasta
        for(var x = 0; x < selected.length; x++){
            
            
                //(console.log(selected[x].children[0]);
                
                  if(selected[x].children[0].innerHTML == targetUser){
                    document.getElementById("sel_users").removeChild(selected[x]);
                }
               
        }
        var secret = document.getElementById("forPHPid");
        for(var x = 0; x < secret.children.length; x++){
            if(secret.children[x].value == targetUser){
            secret.removeChild(secret.children[x]);
                                
            }
        }
        
        //console.log(selected);
        //console.log(secret);
        
    }


    function AddUserToList(x){
            var user = x.textContent;
            SelectedUsersList(user);
        }
        
    function SelectedUsersList(user){
            console.log("addtouserlist");
            var field = document.getElementById("osallistujafield");
            
            var sel_users = document.getElementById("sel_users");
            
            var li = document.createElement("li");
         
            var a = document.createElement("a");
            a.textContent = user;
            a.onclick = RemoveUser;
         
            li.appendChild(a);
            li.setAttribute("name", "selected_user");
        
        
            var selected = document.getElementsByName("selected_user");
        
            console.log(selected);
            
            var option = document.createElement("option");
            
            option.value = user;
            option.textContent = user;
            option.selected = true;
            document.getElementById("forPHPid").appendChild(option);
         
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
                emptyUsers();
                document.getElementById("osallistujafield").value = "";
                document.getElementById("osallistujafield").focus();
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
            
            var cookies = GetCookies();
            console.log(cookies);

            var apikey = "";
            var user_cookie = "";

            for(var x = 0; x < cookies.length; x++){

                        var help2 = cookies[x].split("=");
                        //console.log(help2);
                        var helper = help2[0] .replace(" ", "");

                        if(helper == "user"){
                            user_cookie = help2[1];
                        }

                        if(helper == "token"){
                            apikey = help2[1];
                        }

            }
            
            
            
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
            url: '../API/index.php?type=search_user&search_type=list&param=' + user + "&apikey=" + apikey, method: "GET"
             }).fail(function (data) {
                    console.log("fail!");
                    console.log(data.responseText);
                    console.log(data);
                    

            }).done(function (data) {

                //var x = data.kentta["geometry"]["location"];

                var array = [];

                if(data.status === 200){
                    //console.log("Data found");

                        var lookfor;

                        console.log(document.cookie);
                        var slipcookie = document.cookie.split(";");
                        console.log(slipcookie);

                        //haetaan kirjautuneen käyttäjän id ja tallennetaan se.
                        for(var int = 0; int < slipcookie["length"];int++){
                            var parts = slipcookie[int].split("=");

                            if(parts[0].includes("user") == true){
                                lookfor = parts[1];
                            }

                        }

                        console.log(lookfor);

                    for(var x = 0; x<data.data.length;x++){

                        if(data.data)

                        var li = document.createElement("li");
                        var a = document.createElement("a");

                        //käyttäjä ei pysty jakamaan tapahtumaa itselleen
                        if(data.data[x].username !== lookfor){

                        a.onclick = function(){
                            console.log("click");
                            AddUserToList(this);
                        };

                        a.href = "#";
                        a.textContent = data.data[x];
                        a.tagName = "option";

                        //console.log(data.data[x].username);

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
                    }



                } else {
                    console.log("Error or no data found");
                    console.log(data);
                }

                });
            }
    }

    function validateDatetime(){

        var x = document.getElementById("alkamisaika");

        var y = document.getElementById("paattymisaika");

        var z = document.getElementById("alkamispaiva");

        var w = document.getElementById("paattymispaiva");

        var checkBox = document.getElementById("isalldayID");

        console.log(z.value);
        console.log(w.value);

        if(w.value != "" && z.value == ""){  
            document.getElementById("alkaa").style.border = "1px solid red";
            return false;
        } else {
            document.getElementById("alkaa").style.border = "";
        }

        if(w.value == "" && z.value != ""){  
            document.getElementById("loppuu").style.border = "1px solid red";
            return false;
        } else {
            document.getElementById("loppuu").style.border = "";
        }

        if(w.value != "" && z.value != "" && z.value > w.value){
            document.getElementById("alkaa").style.border = "1px solid red";
            var text = "alkaa ennen päättymisen jälkeen";
            document.getElementById("message").textContent = text;
            return false;
        } else {
            document.getElementById("alkaa").style.border = "";
            var text = "";
            document.getElementById("message").textContent = text;
            return true;
        }

        /*
        if(z.value > w.value){
            var text = "alkaa ennen päättymisen jälkeen";
            document.getElementById("message").textContent = text;

        } else {
        document.getElementById("message").textContent = "";            
        }
        */
    }

    function initAutocomplete() {
    var map = new google.maps.Map(document.getElementById('map'), {
      center: {lat: 61.92410999999999, lng: 25.748151000000007},
      zoom: 10,
      mapTypeId: 'roadmap'
    });

    refreshmap();

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
        // onblur="onAutocompleteBlur()

        var input = document.getElementById("sijainti");

        if(input.value != null){
            refreshmap();   
        }
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

            map.setZoom(10);
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

    function GetCookies(){

        var lookfor;

        //console.log(document.cookie);
        var slipcookie = document.cookie.split(";");

        return slipcookie;
    }

    function getSharedUser(){

    var header = document.getElementById("otsikko").value;

    var cookies = GetCookies();
    console.log(cookies);

    var apikey = "";
    var user = "";
        
    for(var x = 0; x < cookies.length; x++){
                
                var help2 = cookies[x].split("=");
                //console.log(help2);
                var helper = help2[0] .replace(" ", "");
                
                if(helper == "user"){
                    user = help2[1];
                }
                
                if(helper == "token"){
                    apikey = help2[1];
                }
                    
    }

    console.log(apikey);
    console.log(user);

        $.ajax({
            url: '../API/index.php?type=EventsSharedTo&index=' + user + '&apikey=' + apikey + '&header=' + header, method: "GET"
             }).fail(function (data) {
                    console.log("fail!");
                    console.log(data.responseText);

            }).done(function (data) {

                //var x = data.kentta["geometry"]["location"];

                var array = [];

                //if events found
                if(data.status === 200){
                    console.log("Data found");
                    console.log(data);

                    //käydään löydetyt jaetut tapahtumat läpi
                    for(var y = 0; y < data.data.length; y++){
                        console.log(data.data[y].username);
                        SelectedUsersList(data.data[y].username);
                    }

                }
        });

        }

    function HandleSubmit(e){
        console.log("HandleSubmit");
        var error = 0;
        
        //Otsikon tarkastaminen
        var otsikko = document.getElementById("otsikko");
        
        if(otsikko.value == null){
            otsikko.style.border = "1px solid red";
            error++;
        } else {
            if(doesHeaderExist === true){
                error++;
            } else {
            otsikko.style.border = "";
            }
        }
        
        if(validateDatetime() != true){
            error++;
        }
        
        if(error < 1){
            console.log("SubmitEvent!");
            document.getElementById("form").submit();
        } else {
            console.log("Errors detected! Submit refused.");
        }
        
    }