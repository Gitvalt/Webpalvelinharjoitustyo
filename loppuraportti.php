<!DOCTYPE php>
<html lang="fi">
    <head>
        <title>Loppudokumentaatio</title>
        <meta charset="utf-8">
        <link href="style/default.css" rel="stylesheet" type="text/css">
        
        <style>
        html {
            background: #f7faff;
        }
        body {
            background: white;
            padding: 20px;
        }
        div {
            border: 1px solid black;
            padding: 10px;
        }
        .person {
            border: 0px;
            border-top: 1px dotted black;
            border-bottom: 1px dotted black;
            margin-bottom: 20px;
            
        }
        
        #sivustorakenne {
            width: 100%;
        }

        </style>
        
    </head>
    <body>
        <h1>Kalenterisovellus harjoitustyödokumentaatio</h1>
             <div>
                <a href="http://student.labranet.jamk.fi/~K1967/web-palvelinohjelmointi/harjoitustyo/">Linkki toimivaan toteutukseen</a>
                 <br>
                Zip-paketin voit ladata tästä.
            </div>
            
            <div>
                <h2>Yleistietoja projektista</h2>
                    <h3>Palautus ja kuvaus</h3>
                    <p>Tämä työ on webohjelmointi(ttms0500) ja webpalvelinohjelminti(ttms0900) kursseja varten tehty yhdistelmä harjoitustyö. Työ esiteltiin 30.11.2016 ja palautettiin opettajalle 6.12.2016</p>
                    <h3>Tekijät</h3>
                    <ul>
                        <li>Valtteri Seuranen, K1967</li>
                        <li>Syri Kasper, K1786</li>
                        <li>Juha-Pekka Tiirikainen, K2049</li>
                    </ul>
                    <h3>Tehtävän kuvaus</h3>
                    <p>Harjoitustyön tehtävänä oli luoda php ja javascript-ohjelmointikielillä valitusta aiheesta toimiva sovellus, jonka avulla voitaisiin demonstroida opiskelijoiden taitoja kursilla opituista taidoista.</p>
                    
                    <p>Me valitsimme projektin aiheeksi eräänlaisen kalenterisovelluksen kehittämisen. Kalenteriin käyttäjä pystyy luomaan oman tunnuksensa ja luomaan itselleen tapahtumia, jotka sitten näytettäisiin javascriptillä generoidussa kalenterissa käyttäjälle. Käyttäjä voisi sitten halutessaan muokata tapahtumia tai omia tietojaan, jakaa luomiaan tapahtumia muille rekisteröityneille käyttäjille.</p> 
                   
            </div>
        
            <div>
            <h2>Käytännön toteutus</h2>
                <h3>Toteutus</h3>
                    
                    <p>Sivuston toimintojen käyttämiseksi loimme REST-rajapintaa, jonka avulla voitaisiin kalenterin toimintoja käyttää ja mahdollisesti tulevaisuudessa kehittää käytettäväksi muiden sovellusten avulla.</p>
                
                <label for="sivustorakenne">Kuvio 1. Sivustorakenne</label>
                <img src="dokumentaatio/sivustorakenne.png" alt="sivustorakenne" id="sivustorakenne">
                
                <h3>MySQL-tietokanta</h3>
                    <b>log</b><p>Taulu, johon kirjataan sisään/uloskirjautumiset, tapahtumien ja käyttäjien lisäykset, muokkaukset, poistot. targetUser määrittää keneen loki vaikuttaa.</p>
                
                    <b>event</b><p>Event-taulu sisältää kaikki käyttäjien luomat tapahtumat. Foreing key omistajan ja käyttäjän käyttäjätunnuksen kanssa.</p>
                
                    <b>user</b>
                    <p>Sisältää kaikki käyttäjän profiili tiedot. "Tunnuksen tyyppi"-kenttää ei ole käytetty projektissa.</p>
                
                    <b>useraccess</b>
                    <p>UserAccess-taulun tietoja käytetään sivuston sisäänkirjautumisen yhteydessä. Jos käyttäjä on kirjautunut sisään, tämä taulu sisältää käyttäjän "avaimen" ja avaimen luontiajan. Avaimen avulla käyttäjä pystyy käyttämään palvelua.</p>
                
                    <b>accounttype</b>
                    <p>Määrittää käyttäjätunnuksen oikeudet järjestelmään. Oletuksena on kaksi tyyppiä "Default" ja "Admin". Tämän taulun tietoje ei olla käytetty projektissa.</p>
                    
                    <b>sharedevent</b>
                    <p>Määrittää tapahtumia, joille käyttäjällä on oikeus, vaikka käyttäjä itse ei tapahtumaa ole luonut. Taulu sisältää tapahtuman id:n, jota jaetaan ja käyttäjä jolle tapahtuma jaetaan,</p>
                    
                    <img src="dokumentaatio/database_illustration.PNG" alt="database">
                <h3>Rest-api</h3>
                <p>Sivusto toimii sen yhteydessä pyörivän REST-rajapinnan avulla. Kutsumalla ajaxilla: <code>./API/index.php?type=x...</code> antaa sivusto vastaukseksi kolmiosaisen vastauksen json-Cmuodossa: 1. status-koodi, 2. palvelimen vastaus, 3. data. Teknisistä syistä api:n mod_rewrite ominaisuus ei toiminut, joten projekti viimeisteltiin ilman sitä. REST-api varmistaa, onko käyttäjä kirjautunut sisään ennen tiedon palauttamista. API myös varmistaa, että onko käyttäjällä tarvittavat oikeudet käsitellä heidän pyytämäänsä tietoa.</p>
                <a href="dokumentaatio/REST-API.txt">RestAPI funktiot</a>
                
                <h3>PHP-funktiot ja kirjautuminen</h3>
                    <label for="login_img">Sisäänkirjautuminen illustraatio</label>
                    <img src="dokumentaatio/kuvia/login.JPG" id="login_img" alt="Login">
                <h3>Javascript funktiot</h3>
                    <p>Javascriptiä harjoitustyössä pääsääntöisesti käytettiin kalenterin generoimiseen ja input-elementtien tietojen varmistamiseen. "CreateEvent.php"ja "EditEvent.php" käyttävät javascript/CreateEvent.js scriptejä tapahtuman tietojen varmentamiseen. 
                    </p>
                    <p>Tapahtumia luodessa tai muokatessa javascriptillä haetaan googlen map apia käyttäen kartta, joka näyttää käyttäjän määrittämän sijainnin. Lisäksi ajaxilla varmistetaan ettei tapahtuman otsikko ole jo määriteltynä ja ajaxilla haetaan mahdollisia käyttäjiä, joille tapahtuma voitaisiin jakaa.</p>
                    <p>Kalenterin luomiseksi käytimme React JS ympäristöä. Kalenteri tarkistaa joka 30s onko uusia tapahtumia määriteltynä. Voi selailla eri kuukauksien tapahtumia navigointipainikkeiden avulla.</p>
                    <h4>Tietojen tarkistaminen</h4>
                    <p>Javascriptillä tarkistetaan tapahtumia ja käyttäjiä luodessa ja muokatessa tietojen validointi. Kun kaikki lomakkeen tiedot ovat hyväksyttäviä niin suoritetaan php-submit. PHP-suorittaa toisen tarkistuksen lomakkeen tiedoille, jonka jälkeen tietojen luominen tai muokkaaminen tapahtuu.</p>
                
            </div>
            <div>
                <h2>Lähdekoodi ja asennus</h2>
                
                <h3>Projektin lataus</h3>
                    
                <h3>Asennus</h3>
                <ul>
                    <li>Vaihda tietokanta/import.sql tiedostossa "testdatabase" haluamaksesi tietokannan nimeksi</li>
                    <li>Suorita tietokantapalvelimelle tietokanta/import.sql</li>
                    <li>Määritä API/sql-connect.php tiedostoon funktioon Connect oikeat arvot, joilla haluamasi palvelimelle otetaan yhteyttä.</li>
                </ul>
                <h3>Toimivan työn katselmointi</h3>
                <a href="http://student.labranet.jamk.fi/~K1967/web-palvelinohjelmointi/harjoitustyo/">Linkki verkkosivulle</a>
            </div>
            
            <div>
            <h2>Ajankäyttö ja itsearvionti</h2>
                <div class="person">
                    <h3>Valtteri Seuranen</h3>
                    <p>Projektissa olisi silti vielä parantamisen varaa, mutta uskon että suurin osa tärkeimmistä osista on olemassa ja toimintakykyinen. Projektissa on minusta tarpeeksi asiaa kummastakin kurssin aiheesta. REST-apin:n kanssa oli ongelmia, mutta se toimii kohtalaisesti.</p>
                    <p>Omasta mielestäni henkilökohtainen arvosanani voisi olla 3-4, koska tein monia osia töistä ja olin pohtinut projektissa huomioon otettavia asioita (autentikoinnin turvallisuus, mysql-tietokannan rakenne...).</p>
                    Ajankäyttöä en erittäin tarkkaan seurannut, mutta uskon omalta osuudeltani tarvittavan työn määrän täyttyneet.
                    Ajankäyttö oli projektin tekemisessä käytetty minun osuudeltani:
                    <ul>
                        <li>REST-rajapinta 60%</li>
                        <li>React JS Kalenteri 30%</li>
                        <li>Tapahtumien generointi 10%</li>
                    </ul>
                </div>
            </div>
    </body>
</html>
