<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTrial extends Model
{
    protected $table   = 'user_trial';

    protected $fillable = ['*'];

    protected $guarded  = [];
}
