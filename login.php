<?php
session_start();
?>
<html lang="fi">
    <head>
        <title>Kirjaudu Sisään</title>
        <meta charset="utf-8">
        <script src="jquery/jquery-3.1.0.min.js"></script>
    </head>
    <script>
    
    function Login(){
        
        var user = document.getElementById("user");
        var password = document.getElementById("password");
        var url = "./API/login/" + user.value;
        console.log(user.value);
        
       
        $.ajax({
            url: url,
            data: {password: password.value},
            method: "POST",
            success: function(data){
                
                console.log(data);
                if(data.data = "200"){
                    console.log("success");
                }
            }
        });
        
    }
    
    </script>
    <body>
        <!--navbar.php-->
        <h1>Kirjaudu sisään</h1>
            <table>
                <tr>
                    <td>Käyttäjätunnus: </td>
                    <td><input type="text" name="user" id="user" required></td>
                </tr>
                <tr>
                    <td>Salasana:</td>
                    <td><input type="password" name="password" id="password" required></td>
                </tr>
            </table>
            <button onclick="Login()">Kirjaudu</button>
        <!--footer.php-->
    </body>
</html>
<?php
echo $_SESSION["token"];
?>