
function LatestLogin(){
	var CookieList = GetCookies();
	
    //index.php?type=log&logtype=$2&user=$1&apikey=$3
    
    $.ajax({
            url: '../API/index.php?type=log&logtype=' + "Login"  + '&user=' + CookieList[0] + '&apikey=' + CookieList[1], method: "GET"
                 }).fail(function (data) {
                        console.log("fail!");
                        console.log(data.responseText);
                        
                }).done(function (data) {
                    console.log("Data found");
                    console.log(data);
        
                    var today = new Date();
                    var last_week = 
                    console.log(today);
                    
                    var server = document.getElementById("server_info");
                    var ul = document.createElement("ul");
                    var li = document.createElement("li");
                    
                    var date = new Date(data.data[0].log_timestamp);
                
                    li.textContent = "Viimeisin kirjautumisesi oli " + date.getDate() + "." + date.getMonth() + "." + date.getFullYear();
                    li.textContent += " " + date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds();
                    
                    ul.appendChild(li);
                    server.appendChild(ul);
                    
                 });
            
}

function EventsEnding(){
    var CookieList = GetCookies();
	
    //index.php?type=log&logtype=$2&user=$1&apikey=$3
    var date = new Date();
    var date2 = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + (date.getDate() + 1);
    
    if(date.getDate() < 10){
        var datenow = date.getFullYear() + "-" + (date.getMonth() + 1) + "-0" + (date.getDate());
    } else {
        var datenow = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + (date.getDate());    
    }
    
    
    var enddate = new Date(date.getFullYear(), (date.getMonth()), (date.getDate() + 7));
    
    console.log("enddate");
    console.log(enddate);
    
    enddate = enddate.getFullYear() + "-" + (enddate.getMonth() + 1) + "-" + enddate.getDate();
    
    console.log("enddate!");
    console.log(enddate);
    
    $.ajax({
            url: '../API/index.php?type=eventSpef&user=' + CookieList[0] + '&start=' + datenow + '&end=' + enddate + '&apikey=' + CookieList[1], method: "GET"
                 }).fail(function (data) {
                        console.log("fail!");
                        console.log(data.responseText);
                        
                }).done(function (data) {
                    console.log("Data found");
                    console.log(data);
                    
                    var server = document.getElementById("server_info");
        
                    var ul = document.createElement("ul");
                    var li = document.createElement("li");
                    var separator = document.createElement("div");
                    separator.className = "separate";
                        
                    var paattyytanaan = [];
                    var paattyyviikko = [];
                    
                    console.log(datenow);
        
                    for(var x = 0; x < data.data.length; x++){
                           if(data.data[x].startDateTime.includes(datenow)){
                               paattyytanaan.push(data.data[x].header);
                           } else {
                               paattyyviikko.push(data.data[x].header);
                           }
                    }
                    console.log("paattyytanaan.length");
                    console.log(paattyytanaan.length);
                    console.log(paattyytanaan);
                    console.log(paattyyviikko);
                    
        
                    //Elements that end today
                    for(var t = 0; t < paattyytanaan.length; t++){
                            li.textContent = "Tapahtuma " + paattyytanaan[t] + " päättyy " + data.data[q].endDateTime;
                            ul.appendChild(li);
                            li = document.createElement("li");
                    }
                    
                    
                    console.log(ul);
                    
                    separator.appendChild(document.createTextNode("Tapahtumat, jotka päättyvät tänään:"));
                    separator.appendChild(document.createElement("hr"));
                    separator.appendChild(ul);
                    
                    server.appendChild(separator);
                    
                    ul = document.createElement("ul");
                    li = document.createElement("li");
                    separator = document.createElement("div");
                    separator.className = "separate";
    
                    //Elements that end during next week
                    for(var q = 0; q < paattyyviikko.length; q++){
                        li.textContent = "Tapahtuma " + paattyyviikko[q] + " päättyy " + data.data[q].endDateTime;
                        ul.appendChild(li);
                    }
        
                    separator.appendChild(document.createTextNode("Tapahtumat jotka päättyvät seuraavan 7 päivän aikana:"));
                    separator.appendChild(document.createElement("hr"));
                    separator.appendChild(ul);
                    server.appendChild(separator);
                
                 });
}

function GetCookies(){

        var lookfor;

        //console.log(document.cookie);
        var slipcookie = document.cookie.split(";");
		
		var user = "";
		var token = "";

		var cookies = slipcookie;
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
		
		var ContentArray = [];
		ContentArray.push(user);
		ContentArray.push(token);
		
		
        return ContentArray;
    }

	
LatestLogin();
EventsEnding();
