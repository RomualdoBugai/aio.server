<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBirth extends Model
{
    protected $table    = 'user_birth';

    protected $fillable = ['date_birth', 'user_id'];

    protected $guarded  = [];
}
