<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserEnterprise extends Model
{
    protected $table    = 'user_enterprise';

    protected $fillable = [
        'user_id', 'enterprise_id', 'is_active'
    ];

    protected $guarded  = [];

}
