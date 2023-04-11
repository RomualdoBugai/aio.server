<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPhone extends Model
{
    protected $table    = 'user_phone';

    protected $fillable = [
    	'international_code',
    	'long_distance',
    	'number',
    	'arm',
    	'default',
    	'is_active',
    	'user_id'
    ];

    protected $guarderd = [];

    public static function check($long_distance = null, $number = null)
    {
        $check = self::where('long_distance', $long_distance)
        ->where('number', $number)
        ->first();
        return ( $check == null ? true : false );
    }

}
