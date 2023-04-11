<?php

namespace App\Services;

use Illuminate\Support\Facades\Config as Config;

/**
 *
 * @author William Novak
 * @version 1.0
 */
class GoogleMaps
{

    /**
     * validate input data
     * @access  public
     * @param   array  $lat
     * @param   array  $lng
     * @return  object [status, error, fail]
     * @version 1.0 2017-02-16
     * @author  William Novak
     */
    public function execute($lat, $lng)
    {
        $url    = "http://maps.googleapis.com/maps/api/geocode/json?latlng={$lat},{$lng}&sensor=true";
        $client = new \GuzzleHttp\Client();

        $res    = $client->get($url);
        $data   = json_decode($res->getBody(), true);
        if ($data['status'] == 'OK')
        {
            return [
                'lat'   => $lat,
                'lng'   => $lng,
                'name'  => $data['results'][0]['formatted_address'],
                #'url'   => "https://www.google.com.br/maps/@{$lat},{$lng},15z&output=embed"
                'url' => "https://www.google.com/maps/embed/v1/place?key=AIzaSyBoRqJe2ioT4TC1cJxrgbFLOzjNqRekTV0&q={$lat},{$lng}"
            ];
        }

    }
}
