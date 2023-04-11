<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserApp extends Model
{
    protected $table    = 'user_app';

    protected $fillable = ['user_id', 'app_id'];

    protected $guarded  = [];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id')->select(['id', 'name', 'email']);
    }

    public function app()
    {
        return $this->hasOne('App\Models\App', 'id', 'app_id')->select(['id', 'name']);
    }

}
