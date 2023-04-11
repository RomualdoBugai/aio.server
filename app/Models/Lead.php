<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $table    = 'lead';

    protected $fillable = [
        'is_active',
        'name',
        'phone',
        'email',
        'description'
    ];

    protected $guarded  = [];

    public static function check($by, $id = null)
    {
        return self::where('phone', preg_replace("/[^0-9]/", "", $by))
        ->orWhere('email', $by)
        ->where('id', '!=', $id)
        ->first();
    }

}
