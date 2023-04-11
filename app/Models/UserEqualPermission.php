<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserEqualPermission extends Model
{
    protected $table    = 'user_equal_permission';

    protected $fillable = [
        'user_equal_id', 'enterprise_id', 'actions'
    ];

    protected $guarded  = [];
}