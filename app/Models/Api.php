<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Api extends Model
{
    protected $table    = 'api';

    protected $fillable = ['id', 'token'];

    protected $guarded  = [];

    /**
    * check if token exists
    * @access public
    * @param  string $token
    * @return bool
    */
    public static function check($token = null)
    {
        if ($token == null)
        {
            return false;
        }
        return (bool) ( self::where('token', $token)->first() != null ? true : false );
    }

}
