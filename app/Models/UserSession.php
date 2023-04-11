<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
    protected $table    = 'user_session';

    protected $fillable = ['id', 'user_id', 'is_active', "timezone"];

    protected $guarded  = [];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

}
