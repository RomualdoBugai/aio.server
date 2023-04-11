<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonNetwork extends Model
{
    protected $table = 'person_network';

    protected $fillable = [
    	'person_id',
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
