<?php

return [
    'timezones'     => function($execute = false) {
        if ($execute)
        {
            $timezones      = file_get_contents("http://api.timezonedb.com/v2/list-time-zone?key=3ZB46YLI47N9&format=json");
            if ($timezones != null) {
                $timezones = json_decode($timezones, true);
                if ( $timezones['status'] == 'OK' ) {
                    return $timezones['zones'];
                }
            }
        }
    },
    'default' => [
        'date_format'   => 'Y-m-d h:i A',
        'timezone'      => 'America/Sao_paulo',
        'input_date_format' => 'Y-m-d',
        'locale'        => 'PT-BR'
    ]

];
