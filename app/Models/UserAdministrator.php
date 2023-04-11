<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAdministrator extends Model
{
    protected $table   = 'user_administrator';

    protected $fillable = ['user_id', 'insert_user_id'];

    protected $guarded  = [];
    
}
