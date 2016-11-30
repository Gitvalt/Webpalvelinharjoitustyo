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



require("../API/sql-connect.php");



?>
<html lang="fi">
    <head>
        <title>Tapahtuman</title>
        <meta charset="utf-8">
        
        <link href="style/createevent.css" type="text/css" rel="stylesheet">
        
        <script src="../jquery/jquery-3.1.0.min.js"></script>
        <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
        <script src="javascript/CreateEvent.js"></script>
    </head>
    
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCn6UcSrDYdAiiKehVzCYy78AUQpbkFdYI&libraries=places&callback=initAutocomplete"
         async defer>
    </script>

    <body>
        
        <?php include("navbar.php"); ?>
        
        <?php 
        if(isset($_GET["header"]) or isset($_POST["otsikko"])){
        
            $header = @$_GET["header"];
            
            if(isset($_POST["otsikko"])){
                $header = $_POST["otsikko"];
            }
            
            echo $header;
            $response = GetSpecificEventDataHeader($header, $_COOKIE["user"]);
            //print_r($response);
            if(empty($response)){
                echo "Null";
            } else {
                
            //value="<?php echo $response["header"];\?\>"
            $startDateTime = explode(" ", $response["startDateTime"]);
            $endDateTime = explode(" ", $response["endDateTime"]);
                
                
                
        ?>
        

        <!--<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>-->
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"  method="post" id="form">
            <h1>Tapahtuman luominen</h1>

            Otsikko:
            <input type="text" id="otsikko" name="otsikko" value="<?php echo $response["header"];?>">
            <br>

            <label for="kuvaus">Kuvaus:</label>
            <textarea cols="50" rows="10" id="kuvaus" name="kuvaus"><?php echo $response["description"];?></textarea>
            <br>

            <div id="sijainti_style">
                Sijainti:
                <input type="text" id="sijainti" name="sijainti" onkeyup="GoogleApiAutocomplete(this, event)" onblur="onAutocompleteBlur()" value="<?php echo $response["location"];?>">
                <div id="map"></div>
            </div>
            <br>

            <div id="time">
                <div id="alkaa">
                    <label for="alkaa">Tapahtuma alkaa</label>
                    <input type="date" id="alkamispaiva" name="alkudate" onchange="validateDatetime()" value="<?php echo $startDateTime[0];?>"><br>
                    <input type="time" id="alkamisaika" name="alkutime" onchange="validateDatetime()" value="<?php echo $startDateTime[1];?>">
                    <label for="isallday">Kokopäivän</label><input type="checkbox" name="isallday" >
                </div>


                <div id="loppuu">
                    <label for="loppuu">Tapahtuma päättyy:</label>
                    <input type="date"  id="paattymispaiva" name="loppudate" onchange="validateDatetime()" value="<?php echo $endDateTime[0];?>"><br>
                    <input type="time"  id="paattymisaika" name="lopputime" onchange="validateDatetime()" value="<?php echo $endDateTime[1];?>">
                </div>
                <br>
            </div>

            <p id="message"></p>
            <p id="message2"></p>
            <br>

            
            Tapahtuman osallistujat:
            <div id="osallistujat">
                <input type="text" id="osallistujafield"  onkeyup="GetUsers(this, event)">
                <div id="users"></div>
                <br>
            </div>

            <div id="valituthenkilot">
                <div id="sel_users"></div>
                <br>
            </div>
            
            <div id="salainen" style="display:none;">
                <select id="forPHPid" name="forPHP[]" multiple>
                </select>
            </div>
            
            <!--<input type="submit" name="submit">-->
            <button onclick="submitEvent()">Tallenna tapahtuma</button>
        </form>
        <?php
        }
                
        } else {
            echo "Tapahtumaa ei ole määriteltynä";
        } 
        ?>
    </body>
</html>

<?php

$message = "";
$error_counter = 0;

$otsikko = @$_POST["otsikko"];
$kuvaus = @$_POST["kuvaus"];
$sijainti = @$_POST["sijainti"];


$alkutime = @$_POST["alkutime"];
$alkupaiva = @$_POST["alkudate"];

$lopputime = @$_POST["lopputime"];
$loppupaiva = @$_POST["loppudate"];

//otsikon tarkistaminen
if(@$_POST["otsikko"] == null){
    $message .= "Error: Otsikko on määriteltävä!<br>";
    $error_counter++;
}
//---

//paivien tarkistaminen
if(@$alkupaiva == null){
    $message .= "Error: merkitse tapahtuman alkamispaiva!<br>";
    $error_counter++;
} else {
    if(@$alkutime == null or @$_POST["isallday"] == true){
     @$alkutime = "00:00:00";
     @$lopputime = "00:00:00";
    }
}

if(@$loppupaiva == null){
    $message .= "Error: merkitse tapahtuman loppumispaiva!<br>";
    $error_counter++;
} else {
    if(@$lopputime == null or @$_POST["isallday"] == true){
     @$lopputime = "00:00:00";   
    }
}

if(!empty($alkupaiva) or !empty($loppupaiva)){
    
    /*
    echo $alkupaiva . " " . $alkutime;
    echo "<br>";
    echo $loppupaiva . " " . $lopputime;
    */
    
    $alkamisajankohta = date($alkupaiva . " " . $alkutime);
    
    $loppumisajankohta = date($loppupaiva . " " . $lopputime);

    if($alkamisajankohta > $loppumisajankohta){
        $message .= "Error: Tapahtuma merkitty päättyväksi ennen tapahtuman alkamista!<br>";
        $error_counter++;
    } else {
        //$message .= "Error: Ajat OK<br>";
    }
} else {
    $alkamisajankohta = null;
    $loppumisajankohta = null;
}
//---

if($error_counter == 0){
    
    $function = ModifyUserEvent($otsikko, $kuvaus, $alkamisajankohta, $loppumisajankohta, $sijainti, $_COOKIE["user"]);

    if($function === false){
        $message .= "Error: Tapahtuman muokkaaminen ei onnistunut<br>";
    } else {
        $message .= "<b>Tapahtuman muokkaaminen onnistui!</b><br>";
        $index = $function;
        //share event
        
        $shareusers = @$_POST["forPHP"];
        if(!empty($shareusers)){
            $response = ShareFunction($index, $shareusers);
            if($response === false){
                $message .= "Error: Tapahtuman jakaminen käyttäjille epäonnistui";
            }
        }
    }
    
} else {
    
}

echo "<hr>";
if(isset($_POST["otsikko"])){
    echo "<h2>Palvelimen vastaus</h2>";
    //palvelimen vastaukset
    echo $message;
    

}

function ShareFunction($id, $users){
    
    print_r($id);
    print_r($users);
    
    if(count($users) == 0 or $id == null){
        return false;
    }
    
    foreach($users as $person){
        ShareEvent($id["id"], $person);
    }
    
    return true;
}


?>

