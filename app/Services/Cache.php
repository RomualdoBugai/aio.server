<?php

namespace App\Services;

use Illuminate\Support\Facades\Config as Config;

/**
 *
 * @author William Novak
 * @version 1.0
 * @package App\Services
 */

class Cache
{

    const CACHE_EXPIRES = 10;

    protected static $expiresAt;

    public function __construct($controller = null)
    {
        self::$expiresAt = \Carbon\Carbon::now()->addMinutes(self::CACHE_EXPIRES);
    }

    public static function initialize($controller = null)
    {
        if ($controller != null) {
            $controller = (string) strtolower($controller);

            switch ($controller) {
                case 'enterprise':
                    return \App\Services\Cache\Enterprise::class;
                break;
            }
        }
    }

    public static function set($controller, $action, $value)
    {
        $expires = self::$expiresAt;
        $key = self::key($controller, $action);
        if (is_array($value)) {
            $value = (string) json_encode($value);
        }
        \Cache::add($key, $value, $expires);
    }

    public static function check($controller, $action)
    {
        $key = self::key($controller, $action);
        return (bool) \Cache::has($key);
    }

    public static function get($controller, $action)
    {
        $key = self::key($controller, $action);
        if (self::check($controller, $action)) {
            return \Cache::get($key);
        }
    }

    public static function clear($controller, $action)
    {
        $key = self::key($controller, $action);
        \Cache::forget($key);
    }

    protected static function key($controller, $action)
    {
        $controller = (string) strtolower($controller);
        $action = (string) strtolower($action);
        $key = (string) implode('.', [$controller, $action]);
        return $key;
    }


}
