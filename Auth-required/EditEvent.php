<?php
require_once("isValidUser.php");
require_once("../API/sql-connect.php");


?><?php
/*
Header
what happens
where happens
when happens
is it repeative event
with whom i am whit
https://developers.google.com/maps/documentation/javascript/examples/places-searchbox
*/




?>
<html lang="fi">
    <head>
        <title>Tapahtuman muokkaaminen</title>
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
                $header = $_POST["original"];
            }
            
            
            $response = GetSpecificEventDataHeader($header, $_COOKIE["user"]);
           
            //print_r($header);
            if(empty($response)){
                $message .= "Error: Emme löytäneet tapahtumaa $header tietokannasta!";
            } else {
					
				//value="<?php echo $response["header"];\?\>"
				$startDateTime = explode(" ", $response["startDateTime"]);
				$endDateTime = explode(" ", $response["endDateTime"]);
					
				if(isset($_POST["otsikko"])){

					//print_r($_POST);
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
                    } elseif(DoesHeaderExist($otsikko, $_COOKIE["user"]) == true and $otsikko != $_POST["original"]){
                        $message .= "Error: Käyttäjällä on jo tapahtuma otsikolla: <i>$otsikko</i><br>";
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
					} 
					else {
						$alkamisajankohta = null;
						$loppumisajankohta = null;
					}
					//---

					if($error_counter == 0){

						$id = GetEventId($_POST["original"], $_COOKIE["user"]);

						//print_r($id);

						if($id === false){
							 $message .= "Error: Tapahtuman id ei löydetty<br>";
						} else {
							$function = ModifyUserEvent($otsikko, $kuvaus, $alkamisajankohta, $loppumisajankohta, $sijainti, $_COOKIE["user"], $id["id"]);

							if($function === false){
									$message .= "Error: Tapahtuman muokkaaminen ei onnistunut<br>";
								} 
							else {
									$message .= "<b>Tapahtuman muokkaaminen onnistui!</b><br>";
									$index = $function;
									//share event

									$shareusers = @$_POST["forPHP"];

									$response = ShareFunction($id["id"], $shareusers);
									if($response === false){
										$message .= "Error: Tapahtuman jakaminen käyttäjille epäonnistui";
									}

							}
						}
                        
					} //end of error_count == 0
				
				  }//end of if isset post otsikko
                
            } // response is empty end
            
            
            if(!empty($_POST["otsikko"])){
            ?>
            
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"  method="post" id="form">
                <h1>Tapahtuman muokkaaminen</h1>

                Otsikko:
                <input type="hidden" name="original" id="original" value="<?php echo $response["header"];?>">
                <input type="text" id="otsikko" name="otsikko" onkeyup="doesHeaderExist()" value="<?php echo $_POST["otsikko"];?>">
                <p id="error_otsikko"></p>
                <br>

                <label for="kuvaus">Kuvaus:</label>
                <textarea cols="50" rows="10" id="kuvaus" name="kuvaus"><?php echo $_POST["kuvaus"];?></textarea>
                <br>

                <div id="sijainti_style">
                    Sijainti:
                    <input type="text" id="sijainti" name="sijainti" onkeyup="GoogleApiAutocomplete(this, event)" value="<?php echo $_POST["sijainti"];?>">
                    <div id="map"></div>
                </div>
                <br>

                <div id="time">
                    <div id="alkaa">
                        <label for="alkaa">Tapahtuma alkaa</label>
                        <input type="date" id="alkamispaiva" name="alkudate" onchange="validateDatetime()" value="<?php echo $_POST["alkudate"];;?>"><br>
                        <input type="time" id="alkamisaika" name="alkutime" onchange="validateDatetime()" value="<?php echo $_POST["alkutime"];;?>">
                        <label for="isallday">Kokopäivän</label><input type="checkbox" name="isallday" >
                    </div>


                    <div id="loppuu">
                        <label for="loppuu">Tapahtuma päättyy:</label>
                        <input type="date"  id="paattymispaiva" name="loppudate" onchange="validateDatetime()" value="<?php echo $_POST["loppudate"];?>"><br>
                        <input type="time"  id="paattymisaika" name="lopputime" onchange="validateDatetime()" value="<?php echo $_POST["lopputime"];?>">
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

                <script>
                //haetaan käyttäjät joille tapahtumat on jaettu
                getSharedUser();

                </script>

                <input type="button" name="button_submit" onclick="HandleSubmit(this);" value="Tallenna muutokset">
                <!--<button id="submit" onclick="HandleSubmit(this)">Tallenna tapahtuma</button>-->
            </form>
            
                
            <?php  
            } else {
                
            
    ?>


            <!--<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>-->

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"  method="post" id="form">
                <h1>Tapahtuman muokkaaminen</h1>

                Otsikko:
                <input type="hidden" name="original" id="original" value="<?php echo $response["header"];?>">
                <input type="text" id="otsikko" name="otsikko" onkeyup="doesHeaderExist()" value="<?php echo $response["header"];?>">
                <p id="error_otsikko"></p>
                <br>

                <label for="kuvaus">Kuvaus:</label>
                <textarea cols="50" rows="10" id="kuvaus" name="kuvaus"><?php echo $response["description"];?></textarea>
                <br>

                <div id="sijainti_style">
                    Sijainti:
                    <input type="text" id="sijainti" name="sijainti" onkeyup="GoogleApiAutocomplete(this, event)" value="<?php echo $response["location"];?>">
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

                <script>

                getSharedUser();

                </script>

                <input type="button" name="button_submit" onclick="HandleSubmit(this);" value="Tallenna muutokset">
                <!--<button id="submit" onclick="HandleSubmit(this)">Tallenna tapahtuma</button>-->

                <div id="remove">
                    <input type="button" name="remove_button" onclick="HandleRemove(this);" value="Poista tapahtuma">
                </div>
        
        </form>
            <?php
            }

            } else {
                echo "Tapahtumaa ei ole määriteltynä";
            }
            
            if(isset($_POST["button_submit"])){
                echo "<hr>";
                echo "<h2>Palvelimen vastaus:</h2>";
                echo $message;
            }    
        ?>
        
    </body>
</html>

<?php

function ShareFunction($id, $users){
    
    //print_r($id);
    //print_r($users);
    
    //jos käyttäjiä ei ole määriteltynä tai id on tyhjä.
    if($id == null){
        return false;
    }

    ShareReplace($id, $users);
    
    return true;
}


?>

