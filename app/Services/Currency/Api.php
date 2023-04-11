<?php

namespace App\Services\Currency;

/**
 *
 * @author William Novak
 * @version 1.0
 */
class Api
{

    /**
     * validate input data
     * @access  public
     * @param   string  $currency
     * @param   boolean $json (default:true)
     * @return  object [status, error, fail]
     * @version 1.0 2016-12-01
     * @author  William Novak
     */
    public static function execute($currency, $json = true)
    {

        $result     = [];
        $currency   = strtolower($currency);
        $config     = (object) config('fixer-io');
        $url        = $config->url;

        if (!array_key_exists($currency, $url))
        {
            $fail = [
                'status'    => false,
                'message'   => message("currency", "not-found")
            ];

            if ($json == true)
            {
                return response()->json($fail);
            }

            return $fail;
        }

        $url    = $url[$currency];
        $result = file_get_contents($url);
        $data   = json_decode($result, true);
        $rate   = [
            'status'    => true,
            'message'   => message("currency", "found"),
            'currency'  => $currency,
            'day'       => $data['date'],
            'rate'      => $data['rates'][strtoupper($currency)]
        ];

        if ($json == true)
        {    
            return response()->json($rate);
        }

        return $rate;
    }
}
