<?php
require("./API/sql-connect.php");
//$_SESSION[$token sisältää] tarvittavan id
//Kirjautuminen on alussa käytettävä php scriptiä, koska javascriptillä ei voida sha1 salata lähetettävää salasanaa.

$message = "";

if(isset($_POST["submit"])){
    
    $user = htmlspecialchars(@$_POST["user"]);
    $pass = htmlspecialchars(@$_POST["password"]);
    
    if(!empty($user) or !empty($pass)){

        if(Login($user, $pass) != false){

            if(CreateAccessToken($user) != false){
              $message = "Kirjautuminen onnistui!";
              header("Location: Auth-required/index.php");
            } else {
                //Creating token failed.
                $message = "Kirjautuminen ei onnistunut. Error numero: 3";
            }
        } else {
            //Username or password incorrect
            $message = "Kirjautuminen ei onnistunut. Käyttäjätunnus tai salasana väärin!";
        } 
    } else {
        $message = "Kirjautuminen ei onnistunut. Käyttäjätunnus tai salasana lomake tyhjä.";
    }
}

?>
<html lang="fi">
    <head>
        <title>Kirjaudu Sisään</title>
        <meta charset="utf-8">
        <script src="jquery/jquery-3.1.0.min.js"></script>
        <link href="style/default.css" rel="stylesheet" type="text/css">
        <link href="style/frontpage.css" rel="stylesheet" type="text/css">
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
                    console.log(data.message);
                    //relocate --> auth/*.php
                }
            }
        });
        
    }
    
    </script>
    
    <body>
        <!--navbar.php-->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <h1>Kirjaudu sisään</h1>
            <table>
                <tr>
                    <td>Käyttäjätunnus: </td>
                    <td><input type="text" name="user" id="user" required value="<?php echo @$_POST["user"]; ?>"></td>
                </tr>
                <tr>
                    <td>Salasana:</td>
                    <td><input type="password" name="password" id="password" required value="<?php echo @$_POST["password"]; ?>"></td>
                </tr>
            </table>
            <input type="submit" name="submit" value="Kirjaudu">
            
        </form>
        
        <?php
        echo @$_SESSION["token"] . "<br>";
        echo $message . "<br><br>";
        ?>

        <a href="index.php">Takaisin etusivulle</a>
        <!--footer.php-->
    </body>
</html>
