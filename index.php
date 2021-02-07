<?php

$current_time = time();
$prev_month = strtotime("-1 Month",$current_time);
$next_month = strtotime("+5 Month",$current_time);
/*
print($current_time."<br>");
print($prev_month."<br>");
print($next_month."<br><br>");

print "One Month ago: ". date("D (N) d-m-Y",$prev_month)."<br>";
print "Today: ". date("D (N) d-m-Y",$current_time)."<br>";
print "Next Month: ". date("D (N) d-m-Y",$next_month)."<br>";

$days_prev_month = date("t",$prev_month);
print($days_prev_month."<br><br>");*/


function first_weekday_month($weekday_today,$monthday_today): int 
{
    // $weekday_today = date("N") -> numeric representation from 1(Mo) to 7(So)
    // $monthday_today = date("j") -> day of the month 1-31
    for($i = $monthday_today-1;$i>=1;$i--){
        $monthday_today -= 1;
        $weekday_today -= 1;
        if($weekday_today<1){$weekday_today += 7;}
    }
    return $weekday_today;
}

function last_weekday_month($weekday_today,$monthday_today,$days_this_month): int
{
    for($i=$monthday_today;$i<$days_this_month;$i++){
        if($weekday_today == 7){
            $monthday_today +=1;
            $weekday_today = 1;
        }else{
            $monthday_today += 1;
            $weekday_today += 1;
        }
    }
    return $weekday_today;
}


function create_calender($current_time,$language = "ger"): string
{


    $prev_month = strtotime("-1 Month",$current_time); //unix timestamp for the previous month from today
    $next_month = strtotime("-1 Month",$current_time); //unix timestamp for the next month from today

    $days_prev_month = date("t",$prev_month); // number of days in previous month

    $weekday_today = date("N",$current_time);   // -> numeric representation from 1(Mo) to 7(So)
    $monthday_today = date("j",$current_time);  // -> day of the month 1-31
    $days_this_month = date("t",$current_time); // -> number of days this month

    $first_weekday_month = first_weekday_month($weekday_today,$monthday_today);
    $last_weekday_month = last_weekday_month($weekday_today,$monthday_today,$days_this_month);

    $weekday_count = 0;// detect when to create a new row
    $weekdays_array = [
      ["Monday", "Montag"],
      ["Tuesday", "Dienstag"],
      ["Wednesday","Mittwoch"],
      ["Thursday","Donnerstag"],
      ["Friday","Freitag"],
      ["Saturday","Samstag"],
      ["Sunday","Sonntag"]
    ];

   $used_language = [];

    if($language == "ger"){
        foreach($weekdays_array as $day){
            $used_language[] = $day[1];
        }
    }else{
        foreach($weekdays_array as $day){
            $used_language[] = $day[0];
        }
    }
    //**********************
    //STRING CREATION BEGINS
    //**********************
    $calender_string = "<p>" . date("F",$current_time) . "</p>";
    $calender_string .= "<table class='calender'>"; // calender string which contains the HTML

    //WEEKDAYS HEADER
    $calender_string .= "<tr>";
    foreach($used_language as $day){
        $calender_string .= "<th class='days'>$day</th>";
    }
    $calender_string .= "</tr>";

    //creating cells for previous month
    //fill in missing weekdays (from Mo) to $first_weekday_month
    if($first_weekday_month !== 1){
        $days_to_fill = $first_weekday_month-1;
        $calender_string .= "<tr>";
        for($i=($days_prev_month-$days_to_fill)+1;$i<=$days_prev_month;$i++){ // the + 1 has to be added because a for loop start at index zero [0], so a additional day would be added

            $weekday_count += 1;
            $calender_string .= "<td class='prev_month'>$i</td>";//table cells
        }
    }
    //creating cells and rows for current month
    for($i=1;$i<=$days_this_month;$i++){
        if($weekday_count == 7){
            $weekday_count = 0;
            $calender_string .= "</tr>";//close a row
            $calender_string .= "<tr>"; //open a row
        }
        $weekday_count += 1;
        $calender_string .= "<td class='current_month'>$i</td>";//table cells
    }

    //creating cells for next month
    //fill in missing weekdays to the end of this week
    if($last_weekday_month !== 7){
        $days_next_month = 1; // helper-var to set days of next month
        for($i = $last_weekday_month;$i<7;$i++){
            if($weekday_count == 7){
                $weekday_count = 0;
                $calender_string .= "</tr>";//close a row
                $calender_string .= "<tr>"; //open a row
            }
            $calender_string .= "<td class='next_month'>$days_next_month</td>";//table cells
            $days_next_month +=1;

        }
        $calender_string .= "</tr>";//closing the table

    }



    $calender_string .= "</table>";
    return $calender_string;
}

print(create_calender($next_month));
