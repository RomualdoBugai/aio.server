<?php

namespace App\Services;

use Illuminate\Support\Facades\Config as Config;

/**
 *
 * @author William Novak
 * @version 1.0
 */
class Client
{

    /**
     * validate input data
     * @access  public
     * @param   array  $input
     * @param   string $routes
     * @param   string $version default: '1.0'
     * @return  object [status, error, fail]
     * @version 1.0 2016-12-01
     * @author  William Novak
     */
    public function execute($input, $route, $version = '1.0', $multipart = false)
    {



        /*
        $config = (object) config('client');
        $host   = route($route, ['version' => $version]);
        $url    = str_replace('8000', $config->server->port, $host);
        $data   = array_merge($input, (array) $config->verify);
        $data['lang'] = \App::getLocale();
        $client = new \GuzzleHttp\Client();
        $res    = $client->post($url, ['form_params' => $data]);
        return  json_decode($res->getBody(), true);
        */

        /*
        $config = (object) config('client');
        $host   = route($route, ['version' => $version]);

        $url    = str_replace('http://aplicativosmpa.com/dev/lumbex/app/public/index.php/', $config->server->hostname, $host);
        $data   = array_merge($input, (array) $config->verify);
        $data['lang'] = \App::getLocale();
        $client = new \GuzzleHttp\Client();
        $res    = $client->post($url, ['form_params' => $data]);
        return  json_decode($res->getBody(), true);
        */



        $config = (object) config('client');
        $host   = route($route, ['version' => $version, 'app' => 'logfiscal']);
        $url    = $host;

        $data           = array_merge($input, (array) $config->verify);

        $data['lang']   = \App::getLocale();
        $client         = new \GuzzleHttp\Client();
        if ($multipart == false) {
            $res            = $client->post($url, ['form_params' => $data]);
        } else {
            $newInput = [];

            foreach($data as $k => $v) {
                if (!is_array($v)) {
                    $newInput[] = [
                        'name' => $k,
                        'contents' => $v
                    ];
                } else {
                    $newInput[] = $v;
                }
            }

            $res            = $client->post($url, ['multipart' => $newInput]);
        }

        return  json_decode($res->getBody(), true);


    }
}
