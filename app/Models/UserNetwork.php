<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserNetwork extends Model
{
    protected $table    = 'user_network';

    protected $fillable = [
    	'user_id',
    	'network',
    	'is_active'
    ];

    protected $guarderd = [];

    public static function check($network = null)
    {
        $check = self::where('network', $network)
        ->first();
        return ( $check == null ? true : false );
    }


}
