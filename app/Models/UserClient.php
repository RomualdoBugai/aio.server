<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserClient extends Model
{
    protected $table   = 'user_client';

    protected $fillable = ['user_id', 'client_user_id'];

    protected $guarded  = [];

}
