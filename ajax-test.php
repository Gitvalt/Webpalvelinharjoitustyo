<html lang="en">
    <head>
        <title>AJAX</title>
        <meta charset="utf-8">
    </head>
    <script>
    function printUsers(){
        var x = document.getElementById("output");
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                x.innerHTML += this.responseText;
                
            }
        };
        
        xhttp.open("GET", "http://localhost:8080/html/web-palvelinohjelmointi/Webpalvelinharjoitustyo/API/users/", true);
        
        xhttp.send();
        
    }
        
    function printTable(){
        
    }   
        
    </script>
    
    <body>
    <button onclick="printUsers()">Test</button>
    <div id="output"></div>
    </body>

</html>