<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnterpriseNetwork extends Model
{
    protected $table = 'enterprise_network';

    protected $fillable = [
    	'enterprise_id',
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
