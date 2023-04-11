<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table    = 'user';

    protected $fillable = ['name', 'email', 'password'];

    protected $guarded  = [];

    protected $hidden   = ['password'];

    public static function check($email, $password)
    {
        return self::where('email', $email)
        ->where('password', $password)
        ->first();
    }

    public static function getOneByEmail($email)
    {
        return self::where('email', $email)->first();
    }

    public function addresses()
    {
        return $this->hasMany('App\Models\UserAddress');
    }

    public function equals()
    {
        return $this->hasMany('App\Models\UserEqual');
    }

    public function enterprises()
    {
        return $this->hasMany('App\Models\UserEnterprise');
    }

    public function isEqualAnotherUser()
    {
        return $this->hasOne('App\Models\UserEqual', 'user_id', 'id');
    }

}
