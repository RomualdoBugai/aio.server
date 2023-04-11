<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InviteUser extends Model
{
    protected $table    = 'invite_user';

    protected $fillable = ['user_id', 'email', 'name', 'is_active'];

    protected $guarded  = [];

    public function invited()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

}
