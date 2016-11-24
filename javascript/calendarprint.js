function JavaPrint(){

    var input = document.getElementsByName("selected_date");
    if(input.getMonth != ""){
        
        //input = new Date(input[0]["valueAsNumber"]);
        //var date = new Date(input);
        var date = new Date();
        
        PrintCalender(date);

        document.getElementById("day").value = date.getDate();

        var months = Array(12);
        months[0] = "January"
        months[1] = "February"
        months[2] = "March"
        months[3] = "April"
        months[4] = "May"
        months[5] = "June"
        months[6] = "July"
        months[7] = "August"
        months[8] = "September"
        months[9] = "October"
        months[10] = "November";
        months[11] = "December";
        var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

        document.getElementById("month").value = months[date.getMonth()];
        document.getElementById("year").value = date.getFullYear();
    }
}
 





//Get events from database
function EventsfromDatabase(user, start, end){
    
}

function PrintCalender(date){

    /*
    Prints a calender to <div id=ouput>
    
    input of function is a date in a month, that you want inspect. For example: input: 15.10.2016 -> result: prints October and highlights 15th day of october.
    
    */

    var CookieUser = GetLoggedInUser();

    var date;
    
    var today = new Date();
    
    //aloitus kuukausi
    var startMonth = date.getMonth();
    
    var index = 1;
    
    //var date has unchanged input date while handlerDate contains by default, first day of the input month
    var handlerDate = new Date(date.getFullYear(), date.getMonth(), index, "0", "0", "0", "0");
    
    
    var endMonth = new Date(handlerDate.getFullYear(), date.getMonth() + 1 ,0);
    var startMonth = new Date(handlerDate.getFullYear(), date.getMonth(), 0);
    
    
    
    /*
    var Events = EventsfromDatabase(CookieUser, handlerDate, endMonth);
    console.log("endMonth: " + endMonth);
    console.log("startMonth: " + startMonth);
    
    
    //variables used to create table td element and its text content
    var day;
    var day_content;

    var i = 0;

    var oupt = document.getElementById("output");

    //removes earlier calender, so that it can be replaced by new updated version of it.
    if(oupt.childElementCount != 0){
        while(oupt.childElementCount != 0){
            oupt.removeChild(oupt.firstChild);
        }
    }

    /*
    !!!! In javascript January is 0 and December is 11 !!!!
    Example: date.getDate() == 2 -> month = March
    */

    /*
    var weekday = new Array(7);
    weekday[0]=  "Sunday";
    weekday[1] = "Monday";
    weekday[2] = "Tuesday";
    weekday[3] = "Wednesday";
    weekday[4] = "Thursday";
    weekday[5] = "Friday";
    weekday[6] = "Saturday";
    */
    var weekdays = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
    
    var table = document.createElement("table");
    var tr = document.createElement("tr");


    //if first day of the month is not monday
    if(handlerDate.getDay() != 1){

        console.log("handlerDate: " + handlerDate);

        //days before current weekday without counting current day
        var daystocreate = handlerDate.getDay() - 1;

        //date is in milliseconds, 1 second = 1000 ms
        var multiplier = 1000*60*60*24*daystocreate;


        //shows what date is exmpl. 27
        var daysbeforemonth = handlerDate - multiplier;

        var firstofweek = new Date(daysbeforemonth);

        console.log("First date of week:" + firstofweek);

        var one_day = 1000*60*60*24;

        

        while(daystocreate != 0){
            day = document.createElement("td");
            day.className = "calendar_day_outofrange";

            day_content = document.createElement("p");

            day_content.textContent = firstofweek.getDate() + " " + weekday[firstofweek.getDay()];

            day.appendChild(day_content);

            tr.appendChild(day);


            daystocreate--;
            multiplier = 1000*60*60*24*daystocreate;
            daysbeforemonth = handlerDate - multiplier;
            firstofweek = new Date(daysbeforemonth);
        }
    }

    //number of children with tr minus 1, because of 0 is acceptable number.
    var int = tr.childElementCount - 1;

    var table_elements = table.childElementCount;
    //tulostaa tr elementtiin päiviä. 
    while(handlerDate.getMonth() == startMonth && table_elements < 6){

        //remember 11 = december
        day = document.createElement("td");
        day.className = "calendar_day";
        day_content = document.createElement("p");

        day_content.textContent = handlerDate.getDate() + " " + weekday[handlerDate.getDay()];

        if(handlerDate.getDate() == date.getDate()){
            day.style.backgroundColor = "red";
        }

        day.appendChild(day_content);

        if(int < 6){
            tr.appendChild(day);
            int++;
        }
        else {
            table.appendChild(tr);
            tr = document.createElement("tr");
            tr.appendChild(day);
            int = 0;
        }

        index++;

        handlerDate = new Date(date.getFullYear(), date.getMonth(), index, "0", "0", "0", "0");
        console.log(handlerDate);



    }
    //jos kuukausi päättyy ja viimeinen päivä ei ole sunnuntai --> lisätään loput kalenteri tauluun
    if(tr.childElementCount < 7){
        var x = 1;
       handlerDate = new Date(date.getFullYear(), date.getMonth() + 1, x, "0", "0", "0", "0");

        while(tr.childElementCount < 7){
            
            //Luodaan päivät
            day = document.createElement("td");
            day.className = "calendar_day_outofrange";
            day_content = document.createElement("p");

            day_content.textContent = handlerDate.getDate() + " " + weekday[handlerDate.getDay()];
            day.appendChild(day_content);
            tr.appendChild(day);
            x++;
            handlerDate = new Date(date.getFullYear(), date.getMonth(), x, "0", "0", "0", "0");
        }
            table.appendChild(tr);
    }
    table.appendChild(tr);

    document.getElementById("output").appendChild(table);
    console.log("End while loop");

}
