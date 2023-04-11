<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = 'person';

    protected $fillable = [
        'is_active',
        'national_code',
        'name',
        'description',
        'alias',
        'gender'
    ];

    protected $guarded  = [];

    public static function check($national_code, $id = null)
    {
        return self::where('national_code', $national_code)
        ->where('id', '!=', $id)
        ->first();
    }

    public function addresses()
    {
        return $this->hasMany('App\Models\PersonAddress');
    }

    public function phones()
    {
        return $this->hasMany('App\Models\PersonPhone');
    }

    public function emails()
    {
        return $this->hasMany('App\Models\PersonEmail');
    }

    public function networks()
    {
        return $this->hasMany('App\Models\PersonNetwork');
    }

}
