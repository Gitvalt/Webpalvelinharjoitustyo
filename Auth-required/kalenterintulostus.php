<?php
require("isValidUser.php");
?>
<html lang="en">
    <head>
        <title>Calendarprinting</title>
        <meta charset="utf-8">
        <meta description="This is calender printing test">
        <link href="../style/style.css" rel="stylesheet" type="text/css">
        
          <!-- Reactin pääkirjasto -->
        <script src="https://unpkg.com/react@15.3.2/dist/react.js"></script>
        <!-- Reactin muutokset DOM:iin -->
        <script src="https://unpkg.com/react-dom@15.3.2/dist/react-dom.js"></script>
        <!-- JSX support -->
        <script src="https://unpkg.com/babel-core@5.8.38/browser.min.js"></script>
        
        <script src="../jquery/jquery-3.1.0.min.js"></script>
        
        <script type="text/babel">
        
    var CalendarController = React.createClass({
       render: function(){ 
                    //<input type="date" ref="value" onChange={this.DetectNewDate} />
                    return (
                    <div>
                            
                            <button onClick={this.props.response} value="left"> "Edellinen kuukausi" </button>
                            <button onClick={this.props.response} value="now"> "Nyt" </button>
                            <button onClick={this.props.response} value="right"> "Seuraava kuukausi" </button>
                    </div>
                    );
                    
            }, DetectNewDate: function(value){
                value.preventDefault();
                console.log("Input: " + this.refs.value);
                this.props.onFormSubmit({item: this.refs.value});
               
            }
    });


    
    
    var Calendar = React.createClass({
        getInitialState: function(){
            var dateNow = new Date();
            
            return {PinDate: dateNow, dates: [], formatedDates: []};
        },
        GetUser: function(){

            var lookfor;

            //console.log(document.cookie);
            var slipcookie = document.cookie.split(";");
            //console.log(slipcookie);

            //haetaan kirjautuneen käyttäjän id ja tallennetaan se.
            for(var int = 0; int < slipcookie["length"];int++){
                var parts = slipcookie[int].split("=");

                if(parts[0].includes("user") == true){
                    lookfor = parts[1];
                }

            }

            return lookfor;
        },
        componentDidMount : function(){
            console.log("didmount");
            this.fetchEvents();
            setInterval(this.fetchEvents, 30000);
       },
        ChangePinDate: function(value){
            
            //console.log("value");
            //console.log(value);
            //console.log(value.target.attributes.value.nodeValue);
            //console.log("PinDate oli: " + this.state.PinDate);
            
            var button = value.target.attributes.value.nodeValue;
            
            var apuDate = this.state.PinDate;
            var resultDate = "";
            
            
            
            switch(button){
                case "left":
                    //console.log("left");
                    resultDate = new Date(apuDate.getFullYear(), apuDate.getMonth() - 1, apuDate.getDate());
                break;
                
                case "right":
                    //console.log("right");
                    resultDate = new Date(apuDate.getFullYear(), apuDate.getMonth() + 1, apuDate.getDate());
                break;
                
                case "now":
                    //console.log("now");
                    resultDate = new Date();
                break;
                
                default:
                    console.log("value: " + value);
                    //resultDate = new Date(value);
                break;
            }
            
            //setstate takes time to change
            
            //console.log("ResultDate: " + resultDate);
            this.setState({PinDate: resultDate}, function(){this.fetchEvents()});
            //console.log("Set new pin: " + this.state.PinDate);
            //this.fetchEvents();
        },
        fetchEvents: function(){
        
            console.log("fetchEvent Activated");
           var pinDate = this.state.PinDate;
           var month = pinDate.getMonth();
           
           //var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
           
           //console.log("pindate: " + pinDate);
           
           var start = new Date(pinDate.getFullYear(), pinDate.getMonth(), 0);
           
           var end = new Date(pinDate.getFullYear(), pinDate.getMonth() + 1, 0);
           
           //console.log("start" + start);
           //console.log("end" + end);
           
           var weekdays = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
           
           //how many days to start as fillers
           var fillerStart = "";
           var fillerEnd = "";
           
           //console.log(start.getDate());
           
           
           //first of month sunday
           switch(start.getDay()){
                //first day of the month is sunday
                case 0:
                    fillerStart = "6";
                break;
                
                //first day of the month is monday
                case 1:
                    fillerStart = "0";
                break;
                
                case 2:
                    fillerStart = "1";
                break;
                
                case 3:
                    fillerStart = "2";
                break;
                
                case 4:
                    fillerStart = "3";
                break;
                
                case 5:
                    fillerStart = "4";
                break;
                
                case 6:
                    fillerStart = "5";
                break;
           }
           //console.log("Filler: " + fillerStart);
           //lastday of the month filler
           switch(end.getDay()){
                //last day of the month is sunday
                case 0:
                    fillerEnd = "0";
                break;
                
                //first day of the month is monday
                case 1:
                    fillerEnd = "6";
                break;
                
                case 2:
                    fillerEnd = "5";
                break;
                
                case 3:
                    fillerEnd = "4";
                break;
                
                case 4:
                    fillerEnd = "3";
                break;
                
                case 5:
                    fillerEnd = "2";
                break;
                
                case 6:
                    fillerEnd = "1";
                break;
           }
            
            var dayapu_start = parseInt(start.getDate());
            var day_start = dayapu_start - parseInt(fillerStart);
            var CalendarStart = new Date(start.getFullYear(), start.getMonth(), day_start);
            
            
            var dayapu = parseInt(end.getDate());
            var day = dayapu + parseInt(fillerEnd);
            //console.log("day: " + day);
            
            //getDay = viikonpaiva, getDate = päivän arvo
            var CalendarEnd = new Date(end.getFullYear(), end.getMonth(), day);
            
            /*
            var CalendarStart = start;
            var CalendarEnd = end;
            */
            
            //console.log("CalendarStart");
            //console.log(CalendarStart);
            
            //console.log("CalendarEnd");
            //console.log(CalendarEnd);
            
           var user = this.GetUser();
           
           var day_value = "";
           var day_value_end = "";
           
           if(CalendarStart.getDate() < 10){
                day_value = "0" + CalendarStart.getDate();
           } else {
            day_value = CalendarStart.getDate();
           }
           
           if(CalendarEnd.getDate() < 10){
                day_value_end = "0" + CalendarEnd.getDate();
           } else {
            day_value_end = CalendarEnd.getDate();
           }
           
           
           //lokakuu = 9 because tammikuu = 0
           var inputStart = CalendarStart.getFullYear() + "-" + (CalendarStart.getMonth() + 1)+ "-" + day_value + " 00:00:00";
           
           //console.log(CalendarStart.getDay());
           //console.log(CalendarEnd.getDay());
           
           var inputEnd = CalendarEnd.getFullYear() + "-" + (CalendarEnd.getMonth() + 1) + "-" + day_value_end + " 00:00:00";
           
           //console.log("inputStart");
           //console.log(inputStart);
            
           //console.log("inputEnd");
           //console.log(inputEnd);
            
           if(user == null){
            console.log("!!!!Error!!!!");
           }
        
           //console.log(user);
           var request = $.ajax({
              url: "../API/php-scripts/get-user-events.php?user=" + user + "&start_span=" + inputStart + "&end_span=" + inputEnd,
              method: "GET",
              dataType: "json",
              success: function(data) {
                 console.log(data.data);
                 this.setState({dates: data.data});
                 
                 
                 var formated = this.state.formatedDates;
                 //console.log("formated");
                 //console.log(formated);
                 
                 //var formated = [];
                 
                 var EventsAdded = formated;
                 //console.log(data);
                 
                 //käy läpi jokainen kalenterin rivi
                 for(var i = 0; i < formated.length; i++){
                     
                     //row values
                     var foo = formated[i];
                     
                     //käy läpikäytävän rivin jokainen elementti läpi
                     for(var i2 = 0; i2 < foo.length; i2++){
                        
                        //foo[i] = day
                        var bar = this.state.dates;
                        
                        var foundEventsForDay = [];
                        
                        //käy läpi ajaxilla haetut tapahtumat.
                        for(var i3 = 0; i3 < bar.length; i3++){
                            //console.log(bar[i3].startDateTime);
                            
                            var day = bar[i3].startDateTime;
                            var splittime = day.split(" ");
                            
                            //console.log("splittime:");
                            //console.log(splittime[0]);
                            var variable = "";
                            
                            //console.log("combined:");
                            
                            var dayvalue = "0";
                            
                            if(parseInt(foo[i2][2]) < 10){
                                dayvalue = "0" + foo[i2][2];
                            } else {
                                dayvalue = foo[i2][2];
                            }
                            
                            var combined = variable.concat(foo[i2][0], "-", (foo[i2][1]+1), "-", dayvalue);
                            
                            //console.log(combined);
                            
                            
                            if(combined == splittime[0]){
                            
                                console.log("ping");
                                
                                foundEventsForDay.push(bar[i3]);
                            
                            } else {
                               
                                
                                if(foundEventsForDay.length == 0){
                                    foundEventsForDay.push("");
                                } else {
                                    //do not add new null values
                                }
                                
                            }
                            
                        }
                        EventsAdded[i][i2].push(foundEventsForDay);
                        
                        
                     }  
                 }
                 
                 this.setState({formatedDates: EventsAdded});
                 
		      }.bind(this)
            });
           
            request.fail(function( jqXHR, textStatus ) {
              alert( "Request failed: " + textStatus );
            });
        
           var index = 0;
           var apudate = CalendarStart;
           var helpArray = [];
           var helpdate;
           var week = [];
           
           var element = [];
           
           //console.log(apudate);
           //console.log(CalendarEnd);
           
           
           //index == if stuck in a loop
           //var index = 0;
           //var index_date = 0;
           
           
           
           //first all date of the selected month
           while(apudate.getMonth() != CalendarEnd.getMonth()){
                
                //console.log(apudate.getFullYear());
                //console.log(apudate.getMonth());
                //console.log(apudate.getDate());
                
               
                helpdate = apudate.getDate();
           
                if(week.length == 7){
                        helpArray.push(week);    
                        week = [];
                        element.push(apudate.getFullYear());
                        element.push(apudate.getMonth());
                        element.push(apudate.getDate());
                        
                        week.push(element);
                        element = [];
                        
                    } else {
                        element.push(apudate.getFullYear());
                        element.push(apudate.getMonth());
                        element.push(apudate.getDate());
                        
                        week.push(element);
                        element = [];
                }
             
                 apudate = new Date(apudate.getFullYear(), apudate.getMonth(), apudate.getDate() + 1);
             
                
                if(index > 1000){
                    console.log("Loop!");
                    break;
                }
                
                index++;
                
           }
           
           //console.log(helpArray);
           
           
           //Remaining dates left for month
           while(apudate.getDate() <= CalendarEnd.getDate()){
                
                //console.log(apudate.getFullYear());
                //console.log(apudate.getMonth());
                //console.log("Date: " + apudate.getDate());
                //console.log("Lenght: " + week.length);
                //console.log(week);
                
                helpdate = apudate.getDate();
       
                
                
                
                if(apudate.getDate() == CalendarEnd.getDate()){
                    element.push(apudate.getFullYear());
                        element.push(apudate.getMonth());
                        element.push(apudate.getDate());
                        
                        week.push(element);
                    helpArray.push(week);
                    break;
                    
                } else {


                     if(week.length == 7){
                        helpArray.push(week);    
                        week = [];
                        element.push(apudate.getFullYear());
                        element.push(apudate.getMonth());
                        element.push(apudate.getDate());
                        
                        week.push(element);
                        element = [];
                        
                    } else {
                        element.push(apudate.getFullYear());
                        element.push(apudate.getMonth());
                        element.push(apudate.getDate());
                        
                        week.push(element);
                        element = [];
                }
             

                    apudate = new Date(apudate.getFullYear(), apudate.getMonth(), apudate.getDate() + 1);
                }
                
                if(index > 1000){
                    //console.log("Loop!");
                    break;
                }
                
                index++;
                 
           }
           
           
           this.setState({formatedDates: helpArray});
           //state --> formatedDates
           
        },
       render: function(){
                    
                    
       
                    //console.log("Formateddates:");
                    //console.log(this.state.formatedDates);
                    var apuContainer = [];
                    
                    var month = this.state.PinDate.getMonth();
                    var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                    
                    var target = this.state.formatedDates.map(function(key, value){
                        
                        
                        
                         var i = 0;
                         //key == viikko
                         //console.log("key");
                         //console.log(key);
                         var events = [];
                         
                         //events.push(key);
                         //console.log(events);
                         
                         
                         if(key.length != 0){
                            
                            //käydään jokainen viikonpäivä läpi
                            for(var x = 0; x < key.length; x++){
                                
                                //jos päivälle on haeutta tapahtumia (ajax on päättynyt)
                                if(key[x].length == 4){
                                    //console.log("key x");
                                    //console.log(key[x]);
                                    
                                    //käydään läpi jokainen eventti
                                    var param = [];
                                    for(var y = 0; y < key[x][3].length; y++){
                                        //console.log(key[x][3].length);
                                        var events_length = key[x][3].length;
                                        
                                        var test = document.createElement("br");
                                        
                                        if(events_length > 1){
                                                
                                                //console.log(events_length);
                                                //ei oteta ensimmäistä tyhjää mukaan
                                                //console.log(key[x][3]);
                                                //console.log(key[x][3][y].header);
                                                //console.log(param);
                                                param.push(key[x][3][y].header);
                                                
                                                
                
                                            } else {
                                                //jos tapahtumaa ei ole siirretään tyhjä.
                                                param.push("");
                                            
                                        }
                                        
                                    }
                                    //console.log(param);
                                    events.push(param);
                                }
                                
                            }
                             
                            
                         }
                         
                        
                        //console.log("events::"); 
                        //console.log(events); 
                         
                         
                         
                        // ------------------------------
                         
                        //console.log(events);
                        
                        var monday = "";
                        var tuesday = "";
                        var wendsday = "";
                        var thursday = "";
                        var friday = "";
                        var saturday = "";
                        var sunday = "";
                        
                        
                        if(events.length != 0){
                           monday = events[0].map(function(key, value){
                                var link = "EditEvent.php?header=" + key;
                                return(
                                <div>
                                    <a href={link}>{key}</a>
                                    <br/>
                                </div>
                                );
                            }.bind(this));
                            
                            tuesday = events[1].map(function(key, value){
                                var link = "EditEvent.php?header=" + key;
                                //console.log(key);
                                return(
                                <div>
                                    <a href={link}>{key}</a>
                                    <br/>
                                </div>
                                );
                            }.bind(this));
                            
                            wendsday = events[2].map(function(key, value){
                                var link = "EditEvent.php?header=" + key;
                                return(
                                <div>
                                    <a href={link}>{key}</a>
                                    <br/>
                                </div>
                                );
                            }.bind(this));
                            
                            thursday = events[3].map(function(key, value){
                                var link = "EditEvent.php?header=" + key;
                                return(
                                <div>
                                    <a href={link}>{key}</a>
                                    <br/>
                                </div>
                                );
                            }.bind(this));
                            
                            friday = events[4].map(function(key, value){
                                var link = "EditEvent.php?header=" + key;
                                return(
                                <div>
                                    <a href={link}>{key}</a>
                                    <br/>
                                </div>
                                );
                            }.bind(this));
                            
                            saturday = events[5].map(function(key, value){
                                var link = "EditEvent.php?header=" + key;
                                return(
                                <div>
                                    <a href={link}>{key}</a>
                                    <br/>
                                </div>
                                );
                            }.bind(this));
                            
                            sunday = events[6].map(function(key, value){
                                var link = "EditEvent.php?header=" + key;
                                return(
                                <div>
                                    <a href={link}>{key}</a>
                                    <br/>
                                </div>
                                );
                            }.bind(this));
                            
                            
                        }
                        
                        //Kalenterin tulostaminen ------------------------------
                    
                        //console.log("events");
                        //console.log(events);
                        //console.log(key);
                        //console.log(key);
                        
                        return(
                                <tr>
                                
                                    <td>
                                    {key[0][2]}<br/>
                                    <hr/>
                                    {monday}
                                    </td>
                                    
                                    <td>
                                    {key[1][2]}<br/>
                                    <hr/>
                                    {tuesday}
                                    </td>
                                    
                                    <td>
                                    {key[2][2]}<br/>
                                    <hr/>
                                    {wendsday}
                                    </td>
                                    
                                    <td>
                                    {key[3][2]}<br/>
                                    <hr/>
                                    {thursday}
                                    </td>
                                    
                                    <td>
                                    {key[4][2]}<br/>
                                    <hr/>
                                    {friday}
                                    </td>
                                    
                                    <td>
                                    {key[5][2]}<br/>
                                    <hr/>
                                    {saturday}
                                    </td>
                                    
                                    <td>
                                    {key[6][2]}<br/>
                                    <hr/>
                                    {sunday}
                                    </td>
                                    
                                </tr>
                                );
                        
                    }.bind(this));
                    
                    return(
                    <div>
                    
                        <table>
                            <thead>
                                <tr>
                                    <td colSpan="3">{this.state.PinDate.getFullYear()}, {months[month]}</td>
                                    <td colSpan="4"><CalendarController response={this.ChangePinDate} onFormSubmit={this.specificDate} /></td>
                                </tr>
                                <tr>
                                    <td>Maanantai</td>
                                    <td>Tiistai</td>
                                    <td>Keskiviikko</td>
                                    <td>Torstai</td>
                                    <td>Perjantai</td>
                                    <td>Lauantai</td>
                                    <td>Sunnuntai</td>
                                </tr>
                            </thead>
                            <tbody>
                                {target}
                            </tbody>
                        </table>
                    </div>
                    );
                    
            }, specificDate: function(value){
                console.log(value);
                
                console.log(value.item.valueAsDate);
                var date = value.item.valueAsDate;
                this.setState({PinDate: date, function(){this.fetchEvents()}});
                
            }   
    });

     
        ReactDOM.render(<Calendar />, document.getElementById('output'));
        </script>
        
    </head>
    <body>
        <?php include("navbar.php"); ?>
        
        <div id="output">

        </div>
    </body>
</html>

<?php

?>
