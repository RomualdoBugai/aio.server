<?php
    $calendar       =  null;
    $calendar       = "<div class=\"ui grid seven columns\">";

    $headings = [
        message('calendar', 'week-mon-prefix'),
        message('calendar', 'week-sun-prefix'),
        message('calendar', 'week-tue-prefix'),
        message('calendar', 'week-wed-prefix'),
        message('calendar', 'week-thu-prefix'),
        message('calendar', 'week-fri-prefix'),
        message('calendar', 'week-sat-prefix')
    ];


    $running_day    = date('w',mktime(0,0,0,$month,1,$year));
    $days_in_month  = date('t',mktime(0,0,0,$month,1,$year));
    $days_in_this_week = 1;
    $day_counter    = 0;
    $dates_array    = array();

    $calendar       .= '<div class="row"><div class="column">'.implode('</div><div class="column">',$headings).'</div></div>';
    $calendar       .= '<div class="row">' . PHP_EOL;;

    for($x = 0; $x < $running_day; $x++) {
        $calendar.= '<div class="column"></div>' . PHP_EOL;;
        $days_in_this_week++;
    }

    if ( (int) $today < 10) {
        $today = '0' . $today;
    }

    if ( (int) $todayInMonth < 10) {
        $todayInMonth = '0' . $todayInMonth;
    }

    for($list_day = 1; $list_day <= $days_in_month; $list_day++) {

        if ($list_day < 10) {
            $day = '0' . $list_day;
        } else {
            $day = $list_day;
        }

        $class = ( $today == $list_day && $todayInMonth == $month ? "calendar-today" : null );
        $calendar   .= "<div class=\"column calendar-week {$class}\">" . PHP_EOL;;
        $calendar   .= "<a data-date=\"{$year}-{$month}-{$day}\" data-only-day=\"{$day}\">";
        $calendar   .= "<span>{$day}</span>";


        if (isset($total["{$year}-{$month}-{$day}"])) {
            $calendar   .= "<span class=\"ui tiny floating label red\">{$total["{$year}-{$month}-{$day}"]}</span>";
        }

        $calendar   .= "</a>";
        $calendar   .= '</div>' . PHP_EOL;

        if($running_day == 6) {
            $calendar.= '</div>' . PHP_EOL;
            if(($day_counter+1) != $days_in_month) {
                $calendar.= '<div class="row">';
            }
            $running_day        = -1;
            $days_in_this_week  = 0;
        }

        $days_in_this_week++;
        $running_day++;
        $day_counter++;
    }

    if($days_in_this_week < 8 && $days_in_this_week > 1) {
        for($x = 1; $x <= (8 - $days_in_this_week); $x++) {
            $calendar   .= '<div class="column"></div>' . PHP_EOL;;
        }
    }

    $calendar   .= '</div>';
    $calendar   .= '</div>';


?>

{!! $calendar !!}
