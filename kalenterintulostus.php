<html lang="en">
    <head>
        <title>Calendarprinting</title>
        <meta charset="utf-8">
        <meta description="This is calender printing test">
        <link href="style/style.css" rel="stylesheet" type="text/css">
        
        <script src="javascript/calendarprint.js" type="text/babel"></script>
        
          <!-- Reactin pääkirjasto -->
        <script src="https://unpkg.com/react@15.3.2/dist/react.js"></script>
        <!-- Reactin muutokset DOM:iin -->
        <script src="https://unpkg.com/react-dom@15.3.2/dist/react-dom.js"></script>
        <!-- JSX support -->
        <script src="https://unpkg.com/babel-core@5.8.38/browser.min.js"></script>
        
        <script src="jquery/jquery-3.1.0.min.js"></script>
        
        <script type="text/babel">
        
    var CalendarController = React.createClass({
        getInitialState: function(){
            
            var dateNow = new Date();
            
            return {Month: dateNow};
        },
        componentDidMount : function(){
           
           
           
        },
       render: function(){    
                    return (
                    <div>
                            
                    </div>
                    );
                    
            }   
    });


    
    
    var Calendar = React.createClass({
        getInitialState: function(){
            
            var dateNow = new Date();
            
            return {PinDate: dateNow, dates: [], formatedDates: []};
        },
        GetUser: function(){

            var lookfor;

            console.log(document.cookie);
            var slipcookie = document.cookie.split(";");
            console.log(slipcookie);

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
           var pinDate = this.state.PinDate;
           var month = pinDate.getMonth();
           
           //var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
           
           console.log(month);
           
           var start = new Date(pinDate.getFullYear(), pinDate.getMonth(), 0);
           
           var end = new Date(pinDate.getFullYear(), pinDate.getMonth() + 1, 0);
           
           console.log("start" + start);
           console.log("end" + end);
           
           var weekdays = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
           
           //how many days to start as fillers
           var fillerStart = "";
           var fillerEnd = "";
           
           console.log(start.getDate());
           
           
           //first of month sunday
           switch(start.getDay()){
                //first day of the month is sunday
                case 0:
                    fillerStart = "0";
                break;
                
                //first day of the month is monday
                case 1:
                    fillerStart = "1";
                break;
                
                case 2:
                    fillerStart = "2";
                break;
                
                case 3:
                    fillerStart = "3";
                break;
                
                case 4:
                    fillerStart = "4";
                break;
                
                case 5:
                    fillerStart = "5";
                break;
                
                case 6:
                    fillerStart = "6";
                break;
           }
           
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
            
            console.log("end before end: " + end);
            var dayapu = parseInt(end.getDate());
            var day = dayapu + parseInt(fillerEnd);
            console.log("day: " + day);
            
            //getDay = viikonpaiva, getDate = päivän arvo
            var CalendarEnd = new Date(end.getFullYear(), end.getMonth(), day);
            
            console.log("CalendarEnd");
            console.log(CalendarEnd);
            
           var user = this.GetUser();
           
           var day_value = "";
           var day_value_end = "";
           
           if(start.getDate() < 10){
                day_value = "0" + start.getDate();
           } else {
            day_value = start.getDate();
           }
           
           if(CalendarEnd.getDate() < 10){
                day_value_end = "0" + CalendarEnd.getDate();
           } else {
            day_value_end = CalendarEnd.getDate();
           }
           
           
           //lokakuu = 9 because tammikuu = 0
           var inputStart = start.getFullYear() + "-" + (start.getMonth() + 1)+ "-" + day_value + " 00:00:00";
           
           console.log(CalendarEnd.getDay());
           
           var inputEnd = CalendarEnd.getFullYear() + "-" + (CalendarEnd.getMonth() + 1) + "-" + day_value_end + " 00:00:00";
           
           console.log("inputStart");
           console.log(inputEnd);
            
           if(user == null){
            console.log("!!!!Error!!!!");
           }
        
           console.log(user);
           var request = $.ajax({
              url: "API/php-scripts/get-user-events.php?user=" + user + "&start_span=" + inputStart + "&end_span=" + inputEnd,
              method: "GET",
              dataType: "json",
              success: function(data) {
                 console.log(data.data);
                 this.setState({dates: data.data});
                 
                 var formated = this.state.formatedDates;
                 var EventsAdded = formated;
                 console.log(data);
                 
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
           var apudate = start;
           var helpArray = [];
           var helpdate;
           var week = [];
           
           var element = [];
           
           console.log(apudate);
           console.log(CalendarEnd);
           
           
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
                    console.log("Loop!");
                    break;
                }
                
                index++;
                 
           }
           
           
           this.setState({formatedDates: helpArray});
           //state --> formatedDates
           
                        },
                        
       render: function(){
                    console.log(this.state.formatedDates);
                    var target = this.state.formatedDates.map(function(key, value){
                    
                         var i = 0;
                         console.log(key[value]);
                         var events = "";
                         
                         //if events are defined, [4] contains array of events or [4][0] == ""
                        if(key[value].length > 3) {
                             console.log(key[value].length);
                             console.log(key[value][3].length);
                             
                             /*
                             on olemassa eventtejä
                             [0]=""->palauta tyhjä
                             [1]=""->käy läpi eventit ja palauta otsikot
                             */
                             
                             //if is not only null value
                             if(key[value][3].length >= 2){
                                
                                console.log("!2!");
                                
                                var foobar = key[value][3].map(function(test, test2){
                                    console.log(test);
                                    if(test != null){
                                        return test.header;
                                    }
                                        
                                }.bind(this));
                                
                                console.log(foobar);
                                events = foobar;
                             } else {
                                events = key[value][3][0];
                             }
                        } else {
                            events = ""; 
                        }
              
                         return(
                                <tr>
                                    <td>
                                    {key[0][2]}<br/>
                                    {events}
                                    </td>
                                    
                                    <td>
                                    {key[1][2]}<br/>
                                    {events}
                                    </td>
                                    <td>
                                    {key[2][2]}<br/>
                                    {events}
                                    </td>
                                    <td>
                                    {key[3][2]}<br/>
                                    {events}
                                    </td>
                                    <td>
                                    {key[4][2]}<br/>
                                    {events}
                                    </td>
                                    <td>
                                    {key[5][2]}<br/>
                                    {events}
                                    </td>
                                    <td>
                                    {key[6][2]}<br/>
                                    {events}
                                    </td>
                                </tr>
                                );
                        
                    }.bind(this));
                    
                    return(
                    <div>
                        <table>
                            <thead>
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
                    
            }   
    });

    
        ReactDOM.render(<Calendar />, document.getElementById('output'));
        </script>
        
    </head>
    <body>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="date" name="selected_date" value="">
            <hr>

            Javascript CalendarPrint<br>
            <div id="nav">
            <input type="button" value="JavaScript" onclick="JavaPrint()">

            <input type="text" id="day" readonly>
            <input type="text" id="month" readonly>
            <input type="text" id="year" readonly>

            </div>
        </form>
        <div id="output">

        </div>
    </body>
</html>

<?php

?>
