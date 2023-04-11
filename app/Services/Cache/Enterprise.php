<?php

namespace App\Services\Cache;

class Enterprise extends \App\Services\Cache
{

    public static function count()
    {
        $controller = strtolower(__CLASS__);
        $action     = __FUNCTION__;

        $exists     = parent::check($controller, $action);

        if ($exists == false) {

            $value = function($nullable) {

                $route      = 'enterpriseServiceGet';
                $version    = '1.0';
                $input      = [
                    'is_active' => 1,
                    'count'     => 1
                ];

                $client = new \App\Services\Client();
                $result = $client->execute($input, $route, $version);

                if ($result['status'] == true) {
                    return (int) $result['data']['enterprise'];
                }

                return 0;
            };

            parent::set($controller, $action, $value(null));
        }

        return parent::get($controller, $action);
    }

    public static function reset($action)
    {
        $controller = strtolower(__CLASS__);
        parent::clear($controller, $action);
    }


}
