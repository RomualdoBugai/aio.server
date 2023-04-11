<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInviteRequest extends Model
{
    protected $table    = 'user_invite_request';

    protected $fillable = ['user_id', 'email', "name", "is_active", "token", "app_id", "request"];

    protected $guarded  = [];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function app()
    {
        return $this->hasOne('App\Models\App', 'id', 'app_id');
    }

}
