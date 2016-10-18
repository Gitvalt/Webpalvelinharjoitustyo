<html lang="en">
    <head>
        <title>AJAX</title>
        <meta charset="utf-8">
    </head>
    <script>
    function printUsers(){
        
        
        var x = document.getElementById("output");
        var xhttp = new XMLHttpRequest();
       
        
        var zx = "http://localhost:8080/html/web-palvelinohjelmointi/Webpalvelinharjoitustyo/API/users/";
        var b = ":";
        
        var c = document.getElementsByName("username")[0].value;
        var pass = document.getElementsByName("password")[0].value;
        var email = document.getElementsByName("email")[0].value;
        
        var a = zx+b+c;
        
       
        
        xhttp.open("POST", a, true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                
                
                x.innerHTML += this.responseText;
                
            }
        };
        
     
        xhttp.send("password=" + pass + "&" + "email=" + email);
        
    }
        
    function printTable(){
        
    }   
        
    </script>
    
    <body>
    
    Username: <input type="text" name="username"><br>
    Password: <input type="text" name="password"><br>
    Email: <input type="text" name="email"><br>
        
    <button onclick="printUsers()">Test</button>
    <div id="output"></div>
    </body>

</html>