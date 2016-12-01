
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
                    
                    li.textContent = "Viimeisin kirjautumisesi oli " + data.data[0].log_timestamp;
                    ul.appendChild(li);
                    server.appendChild(ul);
                    
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