<?php

namespace App\Services;

/**
 *
 * @author William Novak
 * @version 1.0
 */
class Dependencies
{

    protected static $map = [
        'bank_account' => 'bankAccountServiceGross'
    ];

    /**
     * validate input data
     * @access  public
     * @param   array  $controller
     * @return  object [status, error, fail]
     * @version 1.0 2016-12-01
     * @author  William Novak
     */
    public static function check($controller)
    {

        $map = array_keys(self::$map);
        if (!in_array($controller, $map)) {
            return false;
        }

        $route      = self::$map[$controller];
        $version    = '1.0';
        $client     = new \App\Services\Client();
        $result     = $client->execute([], $route, $version);
        return      (bool) $result['status'];
    }
}
