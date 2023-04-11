<?php

namespace App\Services\Useful;

use App\Http\Requests;
use Illuminate\Http\Request as Request;
use Illuminate\Support\Facades\Route;

use Session;

class User
{

    static $user                = 'user.id';
    static $name                = 'user.name';
    static $session             = 'session.id';
    static $timezone            = 'user.settings.timezone';
    static $date_format         = 'user.settings.date_format';
    static $input_date_format   = 'user.settings.input_date_format';

    public static function id()
    {
        return (int) Session::get(self::$user);
    }

    public static function name()
    {
        return (string) Session::get(self::$name);
    }

    public static function session()
    {
        return (int) Session::get(self::$session);
    }

    public static function timezone()
    {
        return (string) Session::get(self::$timezone);
    }

    public static function dateFormat()
    {
        return (string) Session::get(self::$date_format);
    }

    public static function inputDateFormat()
    {
        return (string) Session::get(self::$input_date_format);
    }

    public static function create($userSession = [])
    {
        Session::flush();
        Session::put(self::$user,               $userSession['user']                );
        Session::put(self::$name,               $userSession['name']                );
        Session::put(self::$session,            $userSession['session']             );
        Session::put(self::$timezone,           $userSession['timezone']            );
        Session::put(self::$date_format,        $userSession['date_format']         );
        Session::put(self::$input_date_format,  $userSession['input_date_format']   );
    }

    public static function clear($userSession = [])
    {
        Session::put(self::$user,               null);
        Session::put(self::$name,               null);
        Session::put(self::$session,            null);
        Session::put(self::$timezone,           null);
        Session::put(self::$date_format,        null);
        Session::put(self::$input_date_format,  null);
    }

    public static function haveSession()
    {
        return (bool) (self::session() > 0 ? 1 : 0);
    }

}
